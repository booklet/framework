<?php
trait UserRoles
{
    // ROLES
    // move this to separate file to share with clients
    public function isAdmin() {
        return (new StringUntils($this->role))->isInclude('admin') ? true : false;
    }

    public function isCustomerService() {
        return (new StringUntils($this->role))->isInclude('customer_service') ? true : false;
    }

    public function isProductionWorker() {
        return (new StringUntils($this->role))->isInclude('production_worker') ? true : false;
    }

    public function isWeb() {
        return (new StringUntils($this->role))->isInclude('web') ? true : false;
    }

    public function isClient() {
        return (new StringUntils($this->role))->isInclude('client') ? true : false;
    }

    public function isAgency() {
        return (new StringUntils($this->role))->isInclude('agency') ? true : false;
    }
}
