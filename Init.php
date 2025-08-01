<?php
namespace FacturaScripts\Plugins\IeLotesAuto;

use FacturaScripts\Core\Template\InitClass;
use FacturaScripts\Core\Base\AjaxForms\SalesLineHTML;

final class Init extends InitClass
{
    public function init(): void
    {
        SalesLineHTML::addMod(new Mod\SalesLineMod());
    }

    public function update(): void
    {
    }

    public function uninstall(): void
    {
    }
}