<?php
trait UserRoles
{
    // ROLES
    // move this to separate file to share with clients
    public function isAdmin() {
        return Util::isStringInclude($this->role, 'admin') ? true : false;
    }

    public function isCustomerService() {
        return Util::isStringInclude($this->role, 'customer_service') ? true : false;
    }

    public function isProductionWorker() {
        return Util::isStringInclude($this->role, 'production_worker') ? true : false;
    }

    public function isWeb() {
        return Util::isStringInclude($this->role, 'web') ? true : false;
    }

    public function isClient() {
        return Util::isStringInclude($this->role, 'client') ? true : false;
    }

    public function isAgency() {
        return Util::isStringInclude($this->role, 'agency') ? true : false;
    }
}
