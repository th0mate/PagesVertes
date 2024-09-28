<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'create:user',
    description: 'Commande permettant la création d\' un utilisateur',
)]
class CreateUserCommand extends Command
{
    private const LOGIN_MIN_LENGTH = 4;
    private const LOGIN_MAX_LENGTH = 20;
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_MAX_LENGTH = 30;
    private const EMAIL_MAX_LENGTH = 255;
    private const CODE_LENGTH = 6;

    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UtilisateurManagerInterface $utilisateurManagerInterface,
        private UtilisateurRepository       $utilisateurRepository
    )
    {
        parent::__construct();
    }

    private function isLoginInvalid(?string $login): bool
    {
        return $login === null || strlen($login) < self::LOGIN_MIN_LENGTH || strlen($login) > self::LOGIN_MAX_LENGTH || $this->utilisateurRepository->findOneBy(["login" => $login]);
    }

    private function isPasswordInvalid(?string $password): bool
    {
        return $password === null || strlen($password) < self::PASSWORD_MIN_LENGTH || strlen($password) > self::PASSWORD_MAX_LENGTH || !preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#', $password);
    }

    private function isEmailInvalid(?string $email): bool
    {
        return $email === null || strlen($email) > self::EMAIL_MAX_LENGTH || !filter_var($email, FILTER_VALIDATE_EMAIL) || $this->utilisateurRepository->findOneBy(["adresseEmail" => $email]);
    }

    private function isCodeInvalid(?string $code): bool
    {
        return $code === null || strlen($code) !== self::CODE_LENGTH || !preg_match('/^[a-zA-Z0-9]+$/', $code) || $this->utilisateurRepository->findOneBy(["code" => $code]);
    }

    protected function configure(): void
    {
        $this
            ->addOption('login', '-l', InputOption::VALUE_OPTIONAL, 'Login de l\'utilisateur')
            ->addOption('password', '-p', InputOption::VALUE_OPTIONAL, 'Mot de passe de l\'utilisateur')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Email de l\'utilisateur')
            ->addOption('code', null, InputOption::VALUE_OPTIONAL, 'Code de l\'utilisateur')
            ->addOption('visibility', null, InputOption::VALUE_NEGATABLE, 'Visibilité de l\'utilisateur')
            ->addOption('admin', null, InputOption::VALUE_NEGATABLE, 'Utilisateur est-il un admin ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getOption('login');
        $password = $input->getOption('password');
        $email = $input->getOption('email');
        $code = $input->getOption('code');
        $visibility = $input->getOption('visibility');
        $admin = $input->getOption('admin');

        $utilisateur = new Utilisateur();
        $utilisateur->setLogin($login);
        $this->utilisateurManagerInterface->processNewUtilisateur($utilisateur, $password);
        $utilisateur->setAdresseEmail($email);
        $utilisateur->setCode($code);
        $utilisateur->setEstVisible($visibility === "true");
        $utilisateur->setRoles($admin === "true" ? ["ROLE_ADMIN"] : []);

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        $io->success("L'utilisateur " . $login . " vient d'être créé");
        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $this->promptForValidInput($io, $input, 'login', 'Veuillez saisir un login', [$this, 'isLoginInvalid'], "Le login doit contenir entre 4 et 20 caractères et être unique.");
        $this->promptForValidInput($io, $input, 'password', 'Veuillez saisir un mot de passe', [$this, 'isPasswordInvalid'], "Le mot de passe doit contenir entre 8 et 30 caractères, avec au moins une lettre minuscule, une lettre majuscule et un chiffre.", true);
        $this->promptForValidInput($io, $input, 'email', 'Veuillez saisir un email', [$this, 'isEmailInvalid'], "L'email doit être valide, unique et ne doit pas dépasser 255 caractères.");
        $this->promptForValidInput($io, $input, 'code', 'Veuillez saisir un code', [$this, 'isCodeInvalid'], "Le code doit contenir exactement 6 caractères alphanumériques et être unique.", false, true);

        if ($input->getOption('visibility') === null) {
            $visibility = $io->confirm("Veuillez saisir une visibilité", true);
            $visibility = $visibility ? "true" : "false";
            $input->setOption('visibility', $visibility);
        }

        if ($input->getOption('admin') === null) {
            $admin = $io->confirm("Veuillez saisir si l'utilisateur est un admin", false);
            $admin = $admin ? "true" : "false";
            $input->setOption('admin', $admin);
        }
    }

    private function promptForValidInput(SymfonyStyle $io, InputInterface $input, string $option, string $question, callable $validationCallback, string $errorMessage, bool $hidden = false, bool $generateCode = false): void
    {
        $value = $input->getOption($option);
        if ($validationCallback($value)) {
            if ($generateCode) {
                $choix = $io->choice('Veuillez choisir un code', ['oui', 'non']);
                if ($choix == 'oui') {
                    $value = $this->askForValidInput($io, $question, $validationCallback, $errorMessage, $hidden);
                } else {
                    $value = $this->generateCodeRandom();
                }
            } else {
                $value = $this->askForValidInput($io, $question, $validationCallback, $errorMessage, $hidden);
            }
        }
        $input->setOption($option, $value);
    }

    private function askForValidInput(SymfonyStyle $io, string $question, callable $validationCallback, string $errorMessage, bool $hidden = false): ?string
    {
        return $hidden
            ? $io->askHidden($question, function ($value) use ($validationCallback, $errorMessage) {
                if ($validationCallback($value)) {
                    throw new \RuntimeException($errorMessage);
                }
                return $value;
            })
            : $io->ask($question, null, function ($value) use ($validationCallback, $errorMessage) {
                if ($validationCallback($value)) {
                    throw new \RuntimeException($errorMessage);
                }
                return $value;
            });
    }

    private function generateCodeRandom(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $code;
    }
}