<?php

namespace Insider\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\CurrencyBundle\Entity\Tariff;
use Insider\UserBundle\Entity\Module;
use Insider\UserBundle\Entity\Role;
use Insider\UserBundle\Entity\User;
use Insider\UserBundle\Entity\Access;

class LoadAdminInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadRole($manager);
        $this->loadTariff($manager);
        $this->loadUser($manager);
        $this->loadModule($manager);
        $this->loadAccess($manager);

        $manager->flush();
    }

    public function loadAccess(ObjectManager $manager)
    {
        $AccessEdit = new Access();
        $AccessEdit->setName('Редактирование');
        $AccessEdit->setCode('EDIT');

        $manager->persist($AccessEdit);

        $AccessView = new Access();
        $AccessView->setName('Просмотр');
        $AccessView->setCode('VIEW');

        $manager->persist($AccessView);

        $AccessCreate = new Access();
        $AccessCreate->setName('Создание');
        $AccessCreate->setCode('CREATE');

        $manager->persist($AccessCreate);

        $AccessDelete = new Access();
        $AccessDelete->setName('Удаление');
        $AccessDelete->setCode('DELETE');

        $manager->persist($AccessDelete);
    }

    public function loadRole(ObjectManager $manager)
    {
        $RoleAdmin = new Role();
        $RoleAdmin->setName('Администратор');
        $RoleAdmin->setParentRole(Role::ROLE_ADMIN);
        $this->setReference('admin_role', $RoleAdmin);
        $manager->persist($RoleAdmin);

        $RoleClient = new Role();
        $RoleClient->setName('Клиент');
        $RoleClient->setParentRole(Role::ROLE_CLIENT);
        $this->setReference('client_role', $RoleClient);
        $manager->persist($RoleClient);
    }

    public function loadTariff(ObjectManager $manager)
    {
        $tariff = new Tariff();
        $tariff->setIsDefault(true);
        $tariff->setTitle('Единый тариф');
        $tariff->setPriceFirst(13);
        $tariff->setPriceSecond(10);
        $tariff->setPriceFirstCurrency($this->getReference('dollar'));
        $tariff->setPriceSecondCurrency($this->getReference('dollar'));

        $this->setReference('tariff', $tariff);
        $manager->persist($tariff);
    }

    public function loadUser(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail("varloc2000@gmail.com");
        $admin->setPlainPassword("123123");
        $admin->setSuperAdmin(true);
        $admin->setUsername("admin");
        $admin->setEnabled(true);
        $admin->setRole($this->getReference('admin_role'));
        $admin->setCurrency($this->getReference('dollar'));
        $admin->setStatus(User::STATUS_CHECKED);
        $admin->setPromo($this->generatePromoCode());

        $this->addReference('admin', $admin);

        $manager->persist($admin);

        $client = new User();
        $client->setEmail("prikritie@gmail.com");
        $client->setPlainPassword("123123");
        $client->setSuperAdmin(true);
        $client->setUsername("client");
        $client->setEnabled(true);
        $client->setRole($this->getReference('client_role'));
        $client->setCurrency($this->getReference('euro'));
        $client->setStatus(User::STATUS_CHECKED);
        $client->setPromo($this->generatePromoCode());
        $client->setBalance(100.5);

        $this->addReference('client', $client);

        $manager->persist($client);
    }

    public function loadModule(ObjectManager $manager)
    {
        $ModuleUser = new Module();
        $ModuleUser->setName('Пользователи');
        $ModuleUser->setCode('USER');

        $manager->persist($ModuleUser);

        $ModuleRole = new Module();
        $ModuleRole->setName('Роли');
        $ModuleRole->setCode('ROLE');

        $manager->persist($ModuleRole);

        $ModuleDelivery = new Module();
        $ModuleDelivery->setName('Доставка');
        $ModuleDelivery->setCode('DELIVERY');

        $manager->persist($ModuleDelivery);

        $ModuleCurrency = new Module();
        $ModuleCurrency->setName('Курсы валют');
        $ModuleCurrency->setCode('CURRENCY');

        $manager->persist($ModuleCurrency);

        $ModuleAgreement = new Module();
        $ModuleAgreement->setName('Соглашения');
        $ModuleAgreement->setCode('AGREEMENT');

        $manager->persist($ModuleAgreement);

        $ModuleTariff = new Module();
        $ModuleTariff->setName('Тарифы');
        $ModuleTariff->setCode('TARIFF');

        $manager->persist($ModuleAgreement);
    }

    /**
     * @return string
     */
    protected function generatePromoCode()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";

        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}