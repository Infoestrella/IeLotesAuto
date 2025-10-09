<?php

namespace FacturaScripts\Plugins\IeLotesAuto\Mod;

use FacturaScripts\Core\Contract\SalesLineModInterface;
use FacturaScripts\Core\Model\Base\SalesDocument;
use FacturaScripts\Core\Model\Base\SalesDocumentLine;
use FacturaScripts\Core\Tools;

class SalesLineMod implements SalesLineModInterface{

    public function apply(SalesDocument &$model, array &$lines, array $formData): void
    {
    }

    public function applyToLine(array $formData, SalesDocumentLine &$line, string $id): void
    {
        $line->linebatch = $formData['batchline_' . $id] ?? null;
        
        $expValue = $formData['expirationdate_' . $id] ?? null;
        $line->expirationdate = (!empty($expValue) && $expValue !== '0000-00-00') ? $expValue : null;
        $line->lineheads = $formData['lineheads_' . $id] ?? null;
    }

    public function assets(): void
    {
    }

    public function map(array $lines, SalesDocument $model): array
    {
        return [];
    }

    public function newModalFields(): array
    {
        return [];
    }

    public function newFields(): array
    {
        return ['linebatch', 'expirationdate', 'lineheads'];
    }

    public function newTitles(): array
    {
        return ['linebatch', 'expirationdate', 'lineheads'];
    }

    public function renderField(string $idlinea, SalesDocumentLine $line, SalesDocument $model, string $field): ?string
    {
        if ($model->modelClassName() == 'FacturaCliente' || $model->modelClassName() == 'AlbaranCliente') {       
            if ($field === 'linebatch') {
                return $this->batchLine($idlinea, $line, $model);
            }

            if ($field === 'expirationdate') {
                return $this->expirationDate($idlinea, $line, $model);
            }

            if ($field === 'lineheads') {
                return $this->lineheads($idlinea, $line, $model);
            }
        }
        return null;
    }

    public function renderTitle(SalesDocument $model, string $field): ?string
    {
        if ($model->modelClassName() == 'FacturaCliente' || $model->modelClassName() == 'AlbaranCliente') { 
            if ($field === 'linebatch') {
                return '<div class="col-lg-1 order-9"> ' . Tools::trans('batch') . '</div>';
            }       

            if ($field === 'expirationdate') {
                return '<div class="col-lg-1 order-9"> ' . Tools::trans('expiration-date') . '</div>';
            }

            if ($field === 'lineheads') {
                return '<div class="col-lg-1 order-3 text-end">' . Tools::trans('heads') . '</div>';
            }
        }
        return null;
    }

    public function getFastLine(SalesDocument $model, array $formData): ?SalesDocumentLine
    {
        return null;
    }

    protected function batchLine($idlinea, $line, $model): string
    {
        $attributes = $model->editable ?
            'name="batchline_' . $idlinea . '" tabindex="3"' :
            'disabled="" tabindex="-1"';

        $batchLine = $line->linebatch ? $line->linebatch : '';

        $product = $line->getProducto();
        if ($product->batchcontrol){
            $batchLine = $line->linebatch ? $line->linebatch : $this->calculateBatch($model);
        }       

        return '<div class="col-sm-2 col-lg-1 order-9">'
                . '<div class="input-group input-group-sm">'
                    . '<input type="text" ' . $attributes . ' value="' . $batchLine . '" class="form-control"/>'
                . '</div>'
            . '</div>';
    }

    protected function calculateBatch($doc): string
    {
        // Convertir la fecha del documento a timestamp
        $fecha = strtotime($doc->fecha);

        // Obtener número de semana ISO (01 a 53)
        $semana = date('W', $fecha);

        // Formar el batch con prefijo "L"
        $lineBatch = 'L' . $semana;

        return $lineBatch;
    }

    protected function expirationDate($idlinea, $line, $model): string
    {

        $attributes = $model->editable ?
            'name="expirationdate_' . $idlinea . '" tabindex="4"' :
            'disabled="" tabindex="-1"';

        $expirationDate = $line->expirationdate ?
            date('Y-m-d', strtotime($line->expirationdate)) :
            $this->calculateExpiration($model, $line);

        return '<div class="col-sm-2 col-lg-1 order-9">'
                . '<div class="input-group input-group-sm">'
                    . '<input type="date" ' . $attributes . ' value="' . $expirationDate . '" class="form-control"/>'
                . '</div>'
            . '</div>';
    }

    protected function calculateExpiration($doc, $line): ?string
    {
        $product = $line->getProducto();
        $expirationPeriod = (isset($product->expirationperiod) && $product->expirationperiod != 0)
            ? $product->expirationperiod
            : null;

        if ($expirationPeriod === null) {
            return null;
        }

        return date('Y-m-d', strtotime($doc->fecha . ' +' . $expirationPeriod . ' days'));
    }

    protected function lineheads($idlinea, $line, $model): string
    {
        $attributes = $model->editable ?
            'name="lineheads_' . $idlinea . '"' :
            'disabled=""';

        $classinput = 'form-control form-control-sm text-lg-end border-top-0 border-bottom-0 rounded-0';
        $lineheads = isset($line->lineheads) ? $line->lineheads : '0';

        return '<div class="col-sm col-lg-1 order-3">'
            . '<div class="d-lg-none mt-3 small">' . Tools::trans('heads') . '</div>'
            . '<input type="number" ' . $attributes . ' value="' . $lineheads . '" class="' . $classinput . '"/>'
            . '</div>';
    }
}