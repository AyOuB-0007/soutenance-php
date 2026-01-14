<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserReservationController extends AbstractController
{
    public function myReservations(ReservationRepository $reservationRepository): Response
    {
        try {
            $user = $this->getUser();
            
            if (!$user) {
                return $this->json(['reservations' => []]);
            }
            
            $reservations = [];
            
            // Si c'est un utilisateur User, chercher par relation
            if ($user instanceof \App\Entity\User) {
                $reservations = $reservationRepository->findBy(
                    ['user' => $user],
                    ['dateHeure' => 'DESC']
                );
            }
            // Si c'est un Personnel (admin), chercher par téléphone
            elseif ($user instanceof \App\Entity\Personnel) {
                $telephone = $user->getTelephone();
                if ($telephone) {
                    $reservations = $reservationRepository->findBy(
                        ['telephone' => $telephone],
                        ['dateHeure' => 'DESC']
                    );
                }
            }
            
            $data = [];
            foreach ($reservations as $reservation) {
                $data[] = [
                    'id' => $reservation->getId(),
                    'date' => $reservation->getDateHeure()->format('d/m/Y'),
                    'time' => $reservation->getDateHeure()->format('H:i'),
                    'persons' => $reservation->getNombrePersonnes(),
                    'phone' => $reservation->getTelephone(),
                    'table' => $reservation->getLaTable() ? $reservation->getLaTable()->getNumero() : null,
                    'notes' => $reservation->getNotes(),
                ];
            }
            
            return $this->json(['reservations' => $data]);
            
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    public function updateMyReservation(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour modifier une réservation.');
                return $this->redirectToRoute('app_home');
            }

            $reservationId = $request->request->get('reservation_id');
            $reservation = $reservationRepository->find($reservationId);

            if (!$reservation) {
                $this->addFlash('error', 'Réservation introuvable.');
                return $this->redirectToRoute('app_home');
            }

            // Vérifier que l'utilisateur peut modifier cette réservation
            $canEdit = false;
            if ($user instanceof \App\Entity\User && $reservation->getUser() === $user) {
                $canEdit = true;
            } elseif ($user instanceof \App\Entity\Personnel && $reservation->getTelephone() === $user->getTelephone()) {
                $canEdit = true;
            }

            if (!$canEdit) {
                $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier cette réservation.');
                return $this->redirectToRoute('app_home');
            }

            // Mettre à jour la réservation
            $date = $request->request->get('date');
            $time = $request->request->get('time');
            $persons = $request->request->get('persons');
            $phone = $request->request->get('phone');
            $notes = $request->request->get('notes');

            if ($date && $time) {
                $dateTime = new \DateTime($date . ' ' . $time);
                $reservation->setDateHeure($dateTime);
            }

            if ($persons) {
                $reservation->setNombrePersonnes((int)$persons);
            }

            if ($phone) {
                $reservation->setTelephone($phone);
            }

            $reservation->setNotes($notes);

            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été modifiée avec succès.');
            return $this->redirectToRoute('app_home');

        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la modification: ' . $e->getMessage());
            return $this->redirectToRoute('app_home');
        }
    }

    public function deleteMyReservation(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['success' => false, 'error' => 'Non connecté']);
            }

            $reservationId = $request->request->get('reservation_id');
            $reservation = $reservationRepository->find($reservationId);

            if (!$reservation) {
                return $this->json(['success' => false, 'error' => 'Réservation introuvable']);
            }

            // Vérifier que l'utilisateur peut supprimer cette réservation
            $canDelete = false;
            if ($user instanceof \App\Entity\User && $reservation->getUser() === $user) {
                $canDelete = true;
            } elseif ($user instanceof \App\Entity\Personnel && $reservation->getTelephone() === $user->getTelephone()) {
                $canDelete = true;
            }

            if (!$canDelete) {
                return $this->json(['success' => false, 'error' => 'Non autorisé']);
            }

            $entityManager->remove($reservation);
            $entityManager->flush();

            return $this->json(['success' => true]);

        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
