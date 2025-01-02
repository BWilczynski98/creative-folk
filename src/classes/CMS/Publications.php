<?php

namespace PhpMysql\CMS;
use PDOException;
use Exception;
use Imagick;

class Publications
{
    protected ?Base $db = null;
    protected string $base_sql_query = "
                SELECT 
                p.id, p.title, p.summary, p.content, p.created_at,
                p.id_category, p.id_user, p.id_image, p.published,
                c.name AS category,
                CONCAT(u.first_name, ' ', u.last_name) AS author,
                i.file AS image_file,
                i.alt AS image_alt_text
                
                FROM publications AS p
                    
                JOIN categories AS c ON p.id_category = c.id
                JOIN users AS u ON p.id_user = u.id
                    
                LEFT JOIN images AS i ON p.id_image = i.id ";

    public function __construct(Base $db)
    {
        $this->db = $db;
    }

    public function getById(int $id, bool $published = true): mixed
    {
        $sql = $this->base_sql_query . "WHERE p.id = :id ";

        if ($published) {
            $sql .= "AND p.published = 1";
        }

        return $this->db->executeSQL($sql, ['id' => $id])->fetch();
    }

    public function getAll(bool $published = true, ?int $category = null, ?int $user = null, int $limit = 1000): array
    {
        $args = [
            'category' => $category,
            'category_is_null' => $category,
            'user' => $user,
            'user_is_null' => $user,
            'limit' => $limit,
        ];

        $sql = $this->base_sql_query . " WHERE (p.id_category = :category or :category_is_null is null) AND (p.id_user = :user or :user_is_null is null)";

        if ($published) {
            $sql .= "AND p.published = 1 ";
        }
        $sql .= "ORDER BY p.id DESC LIMIT :limit";

        return $this->db->executeSQL($sql, $args)->fetchAll();
    }

    public function create(array $publication, array $image): bool
    {
        try {
            $this->db->beginTransaction();

            if ($image['path']) {
                $imagick = new Imagick($image['tmp_name']);
                $imagick->cropThumbnailImage(1200, 700);
                $imagick->writeImage($image['path']);

                $sql = "INSERT INTO images (file, alt)
                        VALUES (:file, :alt);";

                $this->db->executeSQL($sql, [$publication['image_file'], $publication['image_alt_text']]);
                $publication['id_image'] = $this->db->lastInsertId();
            }

            unset($publication['image_file'], $publication['image_alt_text']);

            $sql = "INSERT INTO publications (title, summary, content, id_category, id_user, id_image, published)
                    VALUES (:title, :summary, :content, :id_category, :id_user, :id_image, :published);";

            $this->db->executeSQL($sql, $publication);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();

            if (file_exists($image['path'])) {
                unlink($image['path']);
            }

            if (($e instanceof PDOException) && ($e->errorInfo[1] === 1062)) {
                return false;
            }

            throw $e;
        }
    }

    public function update(array $publication, $image): bool
    {
        try {

            $this->db->beginTransaction();

            if ($image['path']) {
                $imagick = new Imagick($image['tmp_name']);
                $imagick->cropThumbnailImage(1200, 700);
                $imagick->writeImage($image['path']);

                $sql = "INSERT INTO images (file, alt)
                        VALUES (:file, :alt);";

                $this->db->executeSQL($sql, [$publication['image_file'], $publication['image_alt_text']]);
                $publication['id_image'] = $this->db->lastInsertId();
            }

            unset(
                $publication['category'],
                $publication['created_at'],
                $publication['author'],
                $publication['image_file'],
                $publication['image_alt_text']
            );

            $sql = "UPDATE publications 
                    SET title = :title, summary = :summary, content = :content, 
                        id_category = :id_category, id_image = :id_image, id_user = :id_user,
                        published = :published
                    WHERE id = :id;";

            $this->db->executeSQL($sql, $publication);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();

            if (file_exists($image['path'])) {
                unlink($image['path']);
            }

            if (($e instanceof PDOException) && ($e->errorInfo[1] === 1062)) {
                return false;
            }

            throw $e;
        }
    }


    public function count(?string $term = null): int
    {
        $args = [
            'term1' => '%' . $term . '%',
            'term2' => '%' . $term . '%',
            'term3' => '%' . $term . '%',
        ];

        $sql = "    SELECT COUNT(*)
                    FROM publications AS p
                    WHERE p.title LIKE :term1
                        OR p.summary LIKE :term2
                        OR p.content LIKE :term3
                    AND p.published = 1;";

        return $this->db->executeSQL($sql, $args)->fetchColumn();
    }

    /**
     * @param string $term Pojedyńczy lub ciąg znaków, które definiują szukany termin w bazie danych np. "Poradnik","Druk"
     * @param int $limit Liczba zwróconych elementów, które spełniają warunki szukanego terminu
     * @param int $offset Liczba do pominięcia pasujących elementów
     * @return array Zwraca tablice elementów, które spełniają warunki szukanego terminu
     */
    public function search(string $term, int $limit = 3, int $offset = 0): array
    {
        $formattedQuery = '%' . $term . '%';
        $args = [
            'term1' => '%' . $term . '%',
            'term2' => '%' . $term . '%',
            'term3' => '%' . $term . '%',
            'limit' => $limit,
            'offset' => $offset,
        ];

        $sql = $this->base_sql_query . "WHERE p.title LIKE :term1
                                        OR p.summary LIKE :term2
                                        OR p.content LIKE :term3
                                       AND p.published = 1
                                    ORDER BY p.id DESC
                                    LIMIT :limit
                                    OFFSET :offset;";

        return $this->db->executeSQL($sql, $args)->fetchAll();
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM publications
                WHERE id = :id;";
            $this->db->executeSQL($sql, ['id' => $id]);
            return true;
        } catch(PDOException $e) {
            if ($e->errorInfo[1] === 1451) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function deleteImage(int $id_image, string $file_path, int $id_publication): bool
    {
        try {
            $sql = "UPDATE publications SET id_image = null
                WHERE id = :id_publication;";
            $this->db->executeSQL($sql, ['id_publication' => $id_publication]);
        } catch (Exception $e) {
            throw $e;
        }

        try {
            $sql = "DELETE FROM images
                WHERE id = :id_image;";
            $this->db->executeSQL($sql, ['id_image' => $id_image]);
        } catch (Exception $e) {
            throw $e;
        }

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        return true;
    }

    public function updateImageAltText(int $id_image, string $alt_text): bool
    {
        try {
            $sql = "UPDATE images SET alt = :alt_text WHERE id = :id_image;";
            $this->db->executeSQL($sql, ['alt_text' => $alt_text,'id_image' => $id_image]);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

}