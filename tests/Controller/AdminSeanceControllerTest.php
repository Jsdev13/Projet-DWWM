<?php

namespace App\Tests\Controller;

use App\Entity\Seance;
use App\Entity\User;
use App\Tests\Support\ReferenceDataTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminSeanceControllerTest extends WebTestCase
{
    use ReferenceDataTrait;

    private const URL = '/dashboard/admin/cours/nouveau';

    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $this->em = $em;

        /** @var UserPasswordHasherInterface $hasher */
        $hasher = $container->get(UserPasswordHasherInterface::class);

        // Données de référence créées via Doctrine ORM si elles n'existent pas déjà.
        $this->ensureAdmin($this->em, $hasher);
        $this->ensureCategorie($this->em);
        $this->ensureCoach($this->em);
        $this->ensureSalle($this->em);
    }

    private function loginAsAdmin(): void
    {
        $admin = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']);
        self::assertNotNull($admin);

        $this->client->loginUser($admin);
    }

    /**
     * Remplit le formulaire avec des valeurs valides, surchargeables champ par champ.
     */
    private function buildForm(\Symfony\Component\DomCrawler\Crawler $crawler, array $overrides = []): \Symfony\Component\DomCrawler\Form
    {
        $categorie = $this->ensureCategorie($this->em);
        $coach = $this->ensureCoach($this->em);
        $salle = $this->ensureSalle($this->em);

        $values = array_merge([
            'seance[name]' => 'PHPUnit Cours ' . uniqid(),
            'seance[categorie]' => (string) $categorie->getId(),
            'seance[level]' => 'Débutant',
            'seance[coach]' => (string) $coach->getId(),
            'seance[date]' => (new \DateTime('+5 days'))->format('Y-m-d'),
            'seance[start_time]' => '10:00',
            'seance[end_time]' => '11:00',
            'seance[salle]' => (string) $salle->getId(),
            'seance[capacity_max]' => '15',
        ], $overrides);

        $form = $crawler->selectButton('Enregistrer le cours')->form();

        foreach ($values as $field => $value) {
            $form[$field] = $value;
        }

        return $form;
    }

    public function testFormPageIsDisplayedWithPopulatedChoices(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Créer un cours');

        // Les listes déroulantes sont alimentées par les entités en base
        self::assertGreaterThan(0, $crawler->filter('#seance_coach option[value!=""]')->count());
        self::assertGreaterThan(0, $crawler->filter('#seance_salle option[value!=""]')->count());
        self::assertGreaterThan(0, $crawler->filter('#seance_categorie option[value!=""]')->count());
    }

    public function testAdminCanCreateSeance(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        self::assertResponseIsSuccessful();

        $uniqueName = 'PHPUnit Cours ' . uniqid();
        $form = $this->buildForm($crawler, ['seance[name]' => $uniqueName]);

        $this->client->submit($form);

        self::assertResponseRedirects('/dashboard/admin');

        // L'enregistrement en base est effectif avec ses relations
        $this->em->clear();
        $seance = $this->em->getRepository(Seance::class)->findOneBy(['name' => $uniqueName]);

        self::assertNotNull($seance, 'La séance doit être enregistrée en base.');
        self::assertSame('Débutant', $seance->getLevel());
        self::assertSame(15, $seance->getCapacityMax());
        self::assertNotNull($seance->getCategorie());
        self::assertNotNull($seance->getCoach());
        self::assertNotNull($seance->getSalle());
        self::assertSame('10:00', $seance->getStartTime()->format('H:i'));
        self::assertSame('11:00', $seance->getEndTime()->format('H:i'));

        // Nettoyage
        $this->em->remove($seance);
        $this->em->flush();
    }

    public function testSuccessFlashMessageIsDisplayedAfterCreation(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $uniqueName = 'PHPUnit Flash ' . uniqid();
        $form = $this->buildForm($crawler, ['seance[name]' => $uniqueName]);

        $this->client->submit($form);
        $this->client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Le cours « ' . $uniqueName . ' » a bien été créé.');

        // Nettoyage
        $this->em->clear();
        $seance = $this->em->getRepository(Seance::class)->findOneBy(['name' => $uniqueName]);
        self::assertNotNull($seance);
        $this->em->remove($seance);
        $this->em->flush();
    }

    public function testFormRejectsInvalidData(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, ['seance[name]' => '']);

        $this->client->submit($form);

        // Le formulaire est réaffiché avec les erreurs (422), pas de redirection
        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'Merci de corriger les champs indiqués ci-dessous.');
        self::assertSelectorTextContains('body', 'Le nom du cours est obligatoire');

    }

    public function testFormRejectsNameTooShort(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, ['seance[name]' => 'ab']);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'Le nom doit contenir au moins 3 caractères');
    }

    public function testFormRejectsPastDate(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, [
            'seance[date]' => (new \DateTime('-1 day'))->format('Y-m-d'),
        ]);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'La date ne peut pas être dans le passé');
    }

    public function testFormRejectsNonPositiveCapacity(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, ['seance[capacity_max]' => '0']);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'Le nombre de places doit être positif');
    }

    public function testFormRejectsMissingLevel(): void
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, ['seance[level]' => '']);

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'Le niveau est obligatoire');
    }

    public function testInvalidSubmissionDoesNotPersistSeance(): void
    {
        $this->loginAsAdmin();

        $before = $this->em->getRepository(Seance::class)->count([]);

        $crawler = $this->client->request('GET', self::URL);
        $form = $this->buildForm($crawler, ['seance[name]' => '']);
        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);

        $this->em->clear();
        self::assertSame($before, $this->em->getRepository(Seance::class)->count([]));
    }

    public function testAnonymousUserCannotAccessForm(): void
    {
        $this->client->request('GET', self::URL);

        self::assertResponseStatusCodeSame(302);
    }

    public function testNonAdminUserIsDenied(): void
    {
        /** @var UserPasswordHasherInterface $hasher */
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $email = 'membre.test@example.com';
        $member = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$member) {
            $member = new User();
            $member->setEmail($email);
            $member->setPrenom('Membre');
            $member->setIsVerified(true);
            $member->setRoles(['ROLE_USER']);
            $member->setPassword($hasher->hashPassword($member, 'membre123'));

            $this->em->persist($member);
            $this->em->flush();
        }

        $this->client->loginUser($member);
        $this->client->request('GET', self::URL);

        self::assertResponseStatusCodeSame(403);
    }

    public function testPostIsRejectedForAnonymousUser(): void
    {
        $this->client->request('POST', self::URL);

        self::assertResponseStatusCodeSame(302);
    }
}
