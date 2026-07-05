<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function index()
    {
        $this->requireAdmin();

        $orderModel = $this->loadModel('Order');
        $productModel = $this->loadModel('Product');
        $userModel = $this->loadModel('User');

        $this->renderView('reports/index', [
            'orders' => $orderModel->getAllOrders(),
            'products' => $productModel->getAllProducts(),
            'users' => $userModel->getAllUsers()
        ], 'Izveštaji');
    }

    public function exportExcel()
    {
        $this->requireAdmin();

        require_once '../vendor/autoload.php';

        $type = isset($_GET['type']) ? $_GET['type'] : 'orders';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($type === 'orders') {
            $this->log('Admin exportovao izvestaj narudzbina u Excel');
            $orderModel = $this->loadModel('Order');
            $orders = $orderModel->getAllOrders();

            $sheet->setTitle('Narudžbine');
            $sheet->fromArray(['ID', 'Korisnik', 'Ukupno (RSD)', 'Status', 'Datum'], null, 'A1');

            $row = 2;
            foreach ($orders as $order) {
                $sheet->fromArray([
                    $order['id'],
                    $order['username'],
                    $order['total_price'],
                    $order['status'],
                    $order['created_at']
                ], null, 'A' . $row);
                $row++;
            }

            $filename = 'narudzbine.xlsx';

        } elseif ($type === 'products') {
            $this->log('Admin exportovao izvestaj proizvoda u Excel');
            $productModel = $this->loadModel('Product');
            $products = $productModel->getAllProducts();

            $sheet->setTitle('Proizvodi');
            $sheet->fromArray(['ID', 'Naziv', 'Kategorija', 'Brend', 'Cena (RSD)', 'Na stanju'], null, 'A1');

            $row = 2;
            foreach ($products as $product) {
                $sheet->fromArray([
                    $product['id'],
                    $product['name'],
                    $product['category_name'],
                    $product['brand_name'],
                    $product['price'],
                    $product['stock']
                ], null, 'A' . $row);
                $row++;
            }

            $filename = 'proizvodi.xlsx';

        } elseif ($type === 'users') {
            $this->log('Admin exportovao izvestaj korisnika u Excel');
            $userModel = $this->loadModel('User');
            $users = $userModel->getAllUsers();

            $sheet->setTitle('Korisnici');
            $sheet->fromArray(['ID', 'Korisničko ime', 'Email', 'Rola', 'Datum registracije'], null, 'A1');

            $row = 2;
            foreach ($users as $user) {
                $sheet->fromArray([
                    $user['id'],
                    $user['username'],
                    $user['email'],
                    $user['role'],
                    $user['created_at']
                ], null, 'A' . $row);
                $row++;
            }

            $filename = 'korisnici.xlsx';
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $this->requireAdmin();

        require_once '../vendor/autoload.php';

        $type = isset($_GET['type']) ? $_GET['type'] : 'orders';

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        if ($type === 'orders') {
            $this->log('Admin exportovao izvestaj narudzbina u PDF');
            $orderModel = $this->loadModel('Order');
            $orders = $orderModel->getAllOrders();

            $html = '<h1>Izveštaj narudžbina</h1>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            $html .= '<thead><tr><th>ID</th><th>Korisnik</th><th>Ukupno (RSD)</th><th>Status</th><th>Datum</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($orders as $order) {
                $html .= '<tr>';
                $html .= '<td>' . $order['id'] . '</td>';
                $html .= '<td>' . $order['username'] . '</td>';
                $html .= '<td>' . $order['total_price'] . '</td>';
                $html .= '<td>' . $order['status'] . '</td>';
                $html .= '<td>' . $order['created_at'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $filename = 'narudzbine.pdf';

        } elseif ($type === 'products') {
            $this->log('Admin exportovao izvestaj proizvoda u PDF');
            $productModel = $this->loadModel('Product');
            $products = $productModel->getAllProducts();

            $html = '<h1>Izveštaj proizvoda</h1>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            $html .= '<thead><tr><th>ID</th><th>Naziv</th><th>Kategorija</th><th>Brend</th><th>Cena (RSD)</th><th>Na stanju</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($products as $product) {
                $html .= '<tr>';
                $html .= '<td>' . $product['id'] . '</td>';
                $html .= '<td>' . $product['name'] . '</td>';
                $html .= '<td>' . $product['category_name'] . '</td>';
                $html .= '<td>' . $product['brand_name'] . '</td>';
                $html .= '<td>' . $product['price'] . '</td>';
                $html .= '<td>' . $product['stock'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $filename = 'proizvodi.pdf';

        } elseif ($type === 'users') {
            $this->log('Admin exportovao izvestaj korisnika u PDF');
            $userModel = $this->loadModel('User');
            $users = $userModel->getAllUsers();

            $html = '<h1>Izveštaj korisnika</h1>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            $html .= '<thead><tr><th>ID</th><th>Korisničko ime</th><th>Email</th><th>Rola</th><th>Datum</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($users as $user) {
                $html .= '<tr>';
                $html .= '<td>' . $user['id'] . '</td>';
                $html .= '<td>' . $user['username'] . '</td>';
                $html .= '<td>' . $user['email'] . '</td>';
                $html .= '<td>' . $user['role'] . '</td>';
                $html .= '<td>' . $user['created_at'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $filename = 'korisnici.pdf';
        }

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}