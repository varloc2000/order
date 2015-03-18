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
        $manager->persist($RoleClient);
    }

    public function loadUser(ObjectManager $manager)
    {
        $User = new User();
        $User->setEmail("varloc2000@gmail.com");
        $User->setPlainPassword("123123");
        $User->setSuperAdmin(true);
        $User->setUsername("admin");
        $User->setEnabled(true);
        $User->setRole($this->getReference('admin_role'));
        $User->setStatus(User::STATUS_CHECKED);

        $this->addReference('user', $User);

        $manager->persist($User);
    }

    public function loadModule(ObjectManager $manager)
    {
        $ModuleVideo = new Module();
        $ModuleVideo->setName('Заказ');
        $ModuleVideo->setCode('ORDER');

        $manager->persist($ModuleVideo);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}