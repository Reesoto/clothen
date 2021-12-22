<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\throwException;

class AddressService
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Address $address
     */
    public function save(Address $address) {
        $this->em->persist($address);
        return $this->em->flush();
    }

    /**
     * @param Address $address
     */
    public function saveEdit(Address $address) {
        return $this->em->flush($address);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id) {
        $address = $this->isExist($id);
        return $address;
    }

    /**
     * @param Address $address
     * @param User $user
     * @return bool
     */
    public function isAllow(Address $address, User $user) {
        return ($address->getUser() === $user ? true : false);
    }

    public function isExist(int $id) {
        $address = $this->em->getRepository(Address::class)->findOneById($id);

        return ($address instanceof Address ? $address : false);
    }

    /**
     * @param Address $address
     * @param User $user
     * @return bool
     */
    public function delete(Address $address, User $user) {
        $autorization = $this->checkManipulationsAuthorization($address, $user);

        if($autorization) {
            $this->em->remove($address);
            $this->em->flush();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Address $address
     * @return mixed
     */
    public function getFormattedAddress(Address $address) {
        $company = '';
        if($address->getCompany()) {
            $company = $address->getCompany() . '<br/>';
        }
        $formattedAddresse = $address->getFirstname() . ' ' . $address->getLastname() . ' (' .
            $address->getPhone() . ')<br/>' .
            $company .
            $address->getAddress() . '</br>' .
            $address->getZipcode() . ' ' . $address->getCity() . '</br>' .
            $address->getCountry();

        return $formattedAddresse;
    }

    /**
     * @param Address $address
     * @param User $user
     * @return bool
     */
    private function checkManipulationsAuthorization(Address $address, User $user) {
        $addressExist = $this->isExist($address->getId());
        $addressOwner = $this->isAllow($address, $user);

        return (!$addressExist || !$addressOwner ? false : true);
    }

}