<?php

namespace Utils;

class FileManage{
    protected $file = [];

    /**
     * quais tipos de arquivo aceita?
     */
    function open($filename){

        if (($file = fopen($filename, "r")) !== false) {
            while (($line = fgetcsv($file, 1000, ",")) !== false) {
                $this->file[] = $line;
            }
            fclose($file);
        }
    }


    function exportCSV(){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorio.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Mês', 'Total de Vendas']);

        foreach ($this->file as $mes => $totalMes) {
            fputcsv($output, [$mes, $totalMes]);
        }

        fclose($output);
        exit;
    }
}
