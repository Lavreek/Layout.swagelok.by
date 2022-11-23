<?php

namespace App\Controller;

use App\Entity\UserRequest;
use App\Entity\UserVisit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $serverData = $request->server->all();
        $cookieData = $request->cookies->all();

        if (isset($cookieData['width'])) {
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

            $UserVisit->setUserYmUid($cookieData['_ym_uid']);
            $UserVisit->setCreatedAt(new \DateTime());
            $EntityManager->persist($UserVisit);
            $EntityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/', name: 'app_create_request', methods: ['POST'])]
    public function indexRequest(
        MailerInterface $mailer,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $formData = $request->request->all();
        $serverData = $request->server->all();
        $cookieData = $request->cookies->all();

        if (isset($formData['emailInput'], $formData['commentInput'], $cookieData['width'])) {
            $EntityManager = $doctrine->getManager();
            $UserRequest = new UserRequest();

            $formData['emailInput'] = addslashes($formData['emailInput']);
            $formData['commentInput'] = addslashes($formData['commentInput']);

            $UserRequest->setUserEmail($formData['emailInput']);
            $UserRequest->setUserComment($formData['commentInput']);
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
                        'user_email' => $formData['emailInput'],
                        'comment' => $formData['commentInput'],
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
