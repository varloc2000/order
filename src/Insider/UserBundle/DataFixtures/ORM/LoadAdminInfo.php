<?php

namespace Insider\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function loadUser(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail("varloc2000@gmail.com");
        $admin->setPlainPassword("123123");
        $admin->setSuperAdmin(true);
        $admin->setUsername("admin");
        $admin->setEnabled(true);
        $admin->setRole($this->getReference('admin_role'));
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
        $client->setStatus(User::STATUS_CHECKED);
        $client->setPromo($this->generatePromoCode());

        $this->addReference('client', $client);

        $manager->persist($client);
    }

    public function loadModule(ObjectManager $manager)
    {
        $ModuleVideo = new Module();
        $ModuleVideo->setName('Заказ');
        $ModuleVideo->setCode('ORDER');

        $ModuleVideo = new Module();
        $ModuleVideo->setName('Пользователи');
        $ModuleVideo->setCode('USER');

        $manager->persist($ModuleVideo);
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