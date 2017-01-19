<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:39
 */
namespace Assay\Communication\Profile {

    use Assay\Core\NamedEntity;

    class PersonProfile extends NamedEntity implements IPersonProfile
    {
        public function getForGreetings():string
        {
        }

        public function enableCommenting():bool
        {
        }

        public function testPrivilege():bool
        {
        }

        public function purgeGroup():bool
        {
        }

        public function setGroup():bool
        {
        }
    }
}