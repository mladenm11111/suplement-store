<?php

/**
 * Upravlja konekcijom na bazu podataka i izvrsavanjem upita.
 * Koristi PDO za sigurnu komunikaciju sa MySQL bazom.
 */
class Database
{
    /**
     * @var string Adresa servera
     */
    private $host = DB_HOST;

    /**
     * @var string Korisnicko ime
     */
    private $user = DB_USER;

    /**
     * @var string Lozinka
     */
    private $password = DB_PASS;

    /**
     * @var string Naziv baze podataka
     */
    private $dbname = DB_NAME;

    /**
     * @var string Port
     */
    private $dbport = DB_PORT;

    /**
     * @var PDO Handler konekcije na bazu
     */
    private $dbh;

    /**
     * @var PDOStatement Pripremljeni SQL upit
     */
    private $stmt;

    /**
     * @var string Poruka o grešci
     */
    private $error;

    /**
     * Uspostavlja konekciju na bazu podataka pri kreiranju objekta.
     *
     * @throws PDOException Ako konekcija ne uspe
     */
    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';port=' . $this->dbport;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Priprema SQL upit za izvrsavanje.
     *
     * @param string $sql SQL upit sa placeholder-ima
     * @return void
     */
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Vezuje konkretnu vrednost za placeholder u pripremljenom upitu.
     *
     * @param string $param Naziv placeholder-a (npr. ':title')
     * @param mixed $value Vrednost koja se vezuje
     * @param mixed $type Tip podatka
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Izvrsava pripremljeni upit.
     *
     * @return bool True ako je upit uspesno izvršen
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Vraca sve rezultate upita kao asocijativni niz.
     *
     * @return array Niz asocijativnih nizova sa rezultatima
     */
    public function results()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vraca jedan rezultat upita kao asocijativni niz.
     *
     * @return array|false Asocijativni niz sa rezultatom, ili false ako nema rezultata
     */
    public function result()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vraca broj redova koje je upit zahvatio.
     *
     * @return int Broj redova
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Vraca ID poslednjeg unetog reda.
     *
     * @return string ID poslednjeg unetog reda
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}