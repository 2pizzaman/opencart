<?php
/**
 * Модель для работы с таблицей partnership
 *
 * Class ModelCatalogPartnership
 */
class ModelCatalogPartnership extends Model {

    /**
     * Метод для добавления записи в таблицу partnership
     */
    public function addPartner($our_array)
    {
        /**
         * Сформируем строку с SQL запросом INSERT
         *
         * Обернём каждое значение для предотвращения попадания вредоносного кода
         * в метод $this->db->escape (если это строка) или путём приведения значения
         * к int/float (в зависимости от типа данных)
         *
         * В нашем случае все поля , кроме age -строки, поэтому к ним применяем $this->db->escape;
         * значение, присваемое полю age приведём к int
         */

        $sql = "INSERT INTO " . DB_PREFIX . "partnership
                   SET name = '" . $this->db->escape($our_array['name']) . "',
                   email = '" . $this->db->escape($our_array['email']) . "',
                   age = '" . (int)$our_array['age'] . "',
                   company = '" . $this->db->escape($our_array['company']) . "',
                   tax_form = '" . $this->db->escape($our_array['tax_form']) . "',
                   file = '" . $this->db->escape($our_array['file']) . "',
                   comment = '" . $this->db->escape($our_array['comment']) . "'";

        

        /**
         * Выполним сформированный запрос
         */
        $this->db->query($sql);
    }
}