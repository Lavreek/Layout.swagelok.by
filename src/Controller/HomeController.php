<?php

namespace App\Controller;

use App\Entity\UserRequest;
use App\Entity\UserRequests;
use App\Entity\UserVisit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/visit', name: 'app_visit', methods: ['POST'])]
    public function getVisit(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $serverData = $request->server->all();
        $requestData = $request->request->all();
        $cookieData = $request->cookies->all();

        if (!isset($cookieData['_ym_uid'])) {
            return new JsonResponse('create');
        }

        if (isset($cookieData['_ym_uid'])) {
            $visit = $doctrine->getRepository(UserVisit::class);
            $client = $visit->findOneBy(['user_ym_uid' => $cookieData['_ym_uid']]);
//            $client = false;

            if (!$client) {
                $EntityManager = $doctrine->getManager();
                $UserVisit = new UserVisit();

                $UserVisit->setUserIp($serverData['REMOTE_ADDR']);
                $UserVisit->setSitePage('root');

                $geo = "";
                if (isset($serverData['GEOIP_COUNTRY_NAME'])) {
                    $geo .= $serverData['GEOIP_COUNTRY_NAME'] . " / ";
                }

                if (isset($serverData['GEOIP_REGION'])) {
                    $geo .= $serverData['GEOIP_REGION'] . " / ";
                }

                if (isset($serverData['GEOIP_CITY'])) {
                    $geo .= $serverData['GEOIP_CITY'] . " / ";
                }
                $UserVisit->setUserGeo($geo);

                $UserVisit->setUserWidth($requestData['Width']);
                $UserVisit->setCreatedAt(new \DateTime());

                if (!isset($cookieData['_ym_uid'])) {
                    $UserVisit->setUserYmUid('undefined-' . $requestData['FINGERPRINT_ID']);

                } else {
                    $UserVisit->setUserYmUid($cookieData['_ym_uid']);
                }

                $UserVisit->setUserFingerprintId($requestData['FINGERPRINT_ID']);
                $EntityManager->persist($UserVisit);
                $EntityManager->flush();

                return new JsonResponse('Done!');
            }
        }

        return new JsonResponse('falied :(');
    }

    #[Route('/', name: 'app_create_request', methods: ['POST'])]
    public function getRequest(
        MailerInterface $mailer,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $requestData = $request->request->all();
        $serverData = $request->server->all();
        $cookieData = $request->cookies->all();

        if (isset($requestData['emailInput'], $requestData['commentInput'])) {
            $EntityManager = $doctrine->getManager();
            $UserRequest = new UserRequest();

            $requestData['emailInput'] = addslashes($requestData['emailInput']);
            $requestData['commentInput'] = addslashes($requestData['commentInput']);

            $UserRequest->setUserEmail($requestData['emailInput']);
            $UserRequest->setUserComment($requestData['commentInput']);
            $UserRequest->setUserWidth($cookieData['width']);
            $UserRequest->setCreatedAt(new \DateTime());

            if (isset($serverData['REMOTE_ADDR'])) {
                $UserRequest->setUserIp($serverData['REMOTE_ADDR']);
            } else {
                $UserRequest->setUserIp("");
            }

            $geo = "";
            if (isset($serverData['GEOIP_COUNTRY_NAME'])) {
                $geo .= $serverData['GEOIP_COUNTRY_NAME'] . " / ";
            }

            if (isset($serverData['GEOIP_REGION'])) {
                $geo .= $serverData['GEOIP_REGION'] . " / ";
            }

            if (isset($serverData['GEOIP_CITY'])) {
                $geo .= $serverData['GEOIP_CITY'] . " / ";
            }
            $UserRequest->setUserGeo($geo);

            if (isset($cookieData['_ym_uid'])) {
                $UserRequest->setUserYmUid($cookieData['_ym_uid']);
            } else {
                $UserRequest->setUserYmUid("");
            }

            if (isset($cookieData['FINGERPRINT_ID'])) {
                $UserRequest->setUserFingerprintId($cookieData['FINGERPRINT_ID']);
            } else {
                $UserRequest->setUserFingerprintId("");
            }

            $UserRequest->setUserYmUid($cookieData['FINGERPRINT_ID']);


            $errors = $validator->validate($UserRequest);

            if (count($errors) < 1) {
                $EntityManager->persist($UserRequest);
                $EntityManager->flush();

                if ($geo == "") {
                    $geo = "неизвестно";
                }

                $email = (new TemplatedEmail())
                    ->from('mail@hy-lok.ru')
                    ->to('alex@fluid-line.ru')
                    ->subject('Новое письмо от сайта hy-lok!')
                    ->htmlTemplate('email/index.html.twig')
                    ->context([
                        'user_email' => $requestData['emailInput'],
                        'comment' => $requestData['commentInput'],
                        'geo' => $geo
                    ])
                ;

                $mailer->send($email);

                return new JsonResponse(['message' => 'Ваша заявка была успешно принята.']);
            }
        }

        return new JsonResponse(['message' => 'К сожалению, ваша заявка не может быть обработана.']);
    }
}
