<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */
namespace Assay\BusinessLogic {

    use Assay\Permission\Privilege\Session;

    interface IBussinessProcess
    {
        public function registrationProcess(array $args):bool;

        public function logOn(array $args,Session $old_session):array;

        public function logOff(Session $session):Session;

        public function passwordChangeProcess(array $args):bool;

        public function passwordRecoveryProcess(array $args,Session $session):bool;

        public function authorizationProcess(Session $session, string $process, string $object):bool;
    }
}