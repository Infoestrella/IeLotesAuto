<?php
namespace FacturaScripts\Plugins\IeLotesAuto;

use FacturaScripts\Core\Template\InitClass;
use FacturaScripts\Core\Base\AjaxForms\SalesLineHTML;
use FacturaScripts\Core\Base\AjaxForms\PurchasesLineHTML;

final class Init extends InitClass
{
    public function init(): void
    {
        $this->loadExtension(new Extension\Model\LineaFacturaCliente());
        $this->loadExtension(new Extension\Model\LineaAlbaranCliente());
        $this->loadExtension(new Extension\Model\LineaFacturaProveedor());
        $this->loadExtension(new Extension\Model\LineaAlbaranProveedor());
        PurchasesLineHTML::addMod(new Mod\PurchasesLineMod());
        SalesLineHTML::addMod(new Mod\SalesLineMod());
    }

    public function update(): void
    {
    }

    public function uninstall(): void
    {
    }
}