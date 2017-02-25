<?php
trait UserRoles
{
    // ROLES
    // move this to separate file to share with clients
    public function isAdmin() {
        return StringUntils::isInclude($this->role, 'admin') ? true : false;
    }

    public function isCustomerService() {
        return StringUntils::isInclude($this->role, 'customer_service') ? true : false;
    }

    public function isProductionWorker() {
        return StringUntils::isInclude($this->role, 'production_worker') ? true : false;
    }

    public function isWeb() {
        return StringUntils::isInclude($this->role, 'web') ? true : false;
    }

    public function isClient() {
        return StringUntils::isInclude($this->role, 'client') ? true : false;
    }

    public function isAgency() {
        return StringUntils::isInclude($this->role, 'agency') ? true : false;
    }
}
