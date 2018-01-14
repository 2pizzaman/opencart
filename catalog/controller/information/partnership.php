<?php

/**
 * Контроллер для формирования страницы "Партнёрство"
 *
 * Class ControllerInformationPartnership
 */
class ControllerInformationPartnership extends Controller
{

    /**
     * Метод для вывода страницы " Партнёрство"
     */
    public function index()
    {


       //setcookie("success_name", 'ffdd',time() +1);
        // setcookie("success_name", null);


        // var_dump($_COOKIE);die;
        /**
         * Подключаем к данному контроллеру файл language
         */
        $this->load->language('information/partnership');

        /**
         * Формируем массив бредкрамбов
         */
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/partnership')
        );

        /**
         * Переменная heading_title - заголовок данной страицы, берем из файла language
         */
        $data['heading_title'] = $this->language->get('heading_title');
        // var_dump($data); var_dump($_COOKIE); die;
        /**
         * Переменная для определения - выводит форму или
         * сообщение об успешной отправке формы
         */

        //$data['success'] = false;


        /**
         * ПРАКТИЧЕСКОЕ ЗАДАНИЕ
         * 1. На странице information/partnership после успешной отправки формы
         * с запросом о сотрудничестве необходимо записывать данные о том,
         * что форма успешно отправлена в куки на 30 дней,
         *
         * и каждый раз при показе страницы information/partnership выводить
         * форму только если нет записи об успешной отправке формы в куки,
         * иначе выводить текст
         * о том, что заявка находится в обработке, а саму форма в таком случае
         * остается скрыта
         */



        if(!empty($_COOKIE["success_name"])) {
              $data['text_cookie_isset'] = $this->request->cookie ['success_name'] . $this->language->get('text_cookie_isset');
          }
        $data['success'] = false;

        /**
         *  Если метод HTTP запрос POST, то
         * выполняем валидацию и сохранение данных из формы
         */
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            /**
             * Если массив files не пуст, то
             * начинаем обработку загруженного файла
             */
            if (!empty($this->request->files)) {
                //   var_dump($_FILES);die;
                /**
                 * Массив допустимых MIME-типов, которые могут содержать загружаемые файлы
                 *  #4. К допустимым типам загружаемого файла необходимо добавить файлы doc и docx
                 */
                $allowed_mime_types = array(
                    'application/excel' => 'application/excel',
                    'application/vndms-excel' => 'application/vndms-excel',
                    'application/x-excel' => 'application/x-excel',
                    'application/x-msexcel' => 'application/x-msexcel',
                    'application/vnd.ms-excel' => 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vndopenxmlformats-officedocumentspreadsheetmlsheet' => 'application/vndopenxmlformats-officedocumentspreadsheetmlsheet ',
                    'application/pdf' => 'application/pdf',
                    'application/msword' => 'application/msword',
                    'application/vnd.ms-word' => 'application/vnd.ms-word',
                    'application/vndopenxmlformats-officedocumentwordprocessingmldocument' => 'application/vndopenxmlformats-officedocumentwordprocessingmldocument'
                );

                /**
                 * MIME тип загружаемого файла с формы
                 */
                $our_mime_type = $this->request->files['file']['type'];

                /**
                 * Если MIME типа загружаемого файла нет среди ключей массива
                 * $allow_mime_types, значит будем выводить ошибку о типе загружаемого файла;
                 */
                if (!isset($allowed_mime_types[$our_mime_type])) {
                    $data['error']['file'] = $this->language->get('error_file_type');
                }

                /**
                 *  #2. Необходимо добавить валидацию для поля "Файл" - нельзя загружать файл размером более 1 МБ
                 */
                $our_size_file = $this->request->files['file']['size'];
                if ((int)$our_size_file > 1048576) {  //1048576
                    $data['error']['file'] = $this->language->get('error_file_size');
                    //  var_dump($data);die;
                }

            }

            /**
             * Поключаем модель к контроллеру
             */
            $this->load->model('catalog/partnership');

            /**
             * Валидация поля name
             */
            if (!$this->request->post['name']) {
                $data['error']['name'] = $this->language->get('error_name');
            }
            /**
             * #3. Необходимо добавить дополнительную валидацию для поля "Имя" - оно должно быть не менее 2-ух символов
             */
            if ((mb_strlen(str_replace(' ', '', $this->request->post['name']))) < 2) {
                $data['error']['name'] = $this->language->get('error_name_2');


                // var_dump( $data['error']);die;
            }

            /**
             *  #8. Добавить валидацию на поле имя - в нем не должно быть цифр
             */
            if (isset($this->request->post['name'])) {
                $name_number = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
                foreach ($name_number as $key => $item) {
                    if (strpbrk(($this->request->post['name']), $item))  //strpos
                        $data['error']['name'] = $this->language->get('error_name_3');
                }
            }

            /**
             * Валидация поля Age
             *
             * #1. Необходимо добавить валидацию для поля "Возраст" - значение в данном поле не может быть менее 18 лет
             */
            if (isset($this->request->post['age'])) {
                if (($this->request->post['age']) > 0 && ($this->request->post['age']) < 18) {
                    $data['error']['age'] = $this->language->get('error_age');
                }
            }

            /**
             * Валидация поля email
             */
            if (!$this->request->post['email']) {
                $data['error']['email'] = $this->language->get('error_email');
            }

            /**
             * Валидация поля company
             */
            if (!$this->request->post['company']) {
                $data['error']['company'] = $this->language->get('error_company');
            }
            /**
             * Валидация поля tax_form
             */
            if (!$this->request->post['tax_form']) {
                $data['error']['tax_form'] = $this->language->get('error_tax_form');
            }

            /**
             * Если массив $data['error'] пуст (это значит, что нет ошибок в форме),
             * то вызываем метод модели addPartner и передаём туда массив с данными из формы
             * для сохранения этих данных в БД
             */
            if (empty($data['error'])) {

                /**
                 * Если есть загруженный файл, делаем перемещение этого файла из папки tmp
                 * туда куда нам нужно
                 */
                if (isset($this->request->files['file']['tmp_name'])) {

                    /**
                     * 7. При сохранении файла мы должны формировать имя файла по следующему шаблону:
                     * дата и время загрузки файла - имя пользователя - расширение.
                     * Например, 24_08_2014_21_31_31_john.pdf
                     */
                    $time_file = date('d_m_Y_G_i');
                    $rashir = explode('.', $this->request->files['file']['name']);
                    $dir = $time_file . '_' . $this->request->post['name'] . '.' . $rashir[1];
                    $this->request->files['file']['name'] = $dir;

                    /**
                     * Темповый путь файла
                     */
                    $tmp_file = $this->request->files['file']['tmp_name'];

                    /**
                     * Путь куда мы сохраняем файл
                     * Используем константу DIR_DOWNLOAD (которая у нас назначается в config.php)
                     */
                    $destination = DIR_DOWNLOAD . $this->request->files['file']['name'];

                    /**
                     * С помощью функции move_uploaded_file делаем перемещение
                     */
                    move_uploaded_file($tmp_file, $destination);

                    /**
                     * Записываем путь хранения нашего файла в массив post,
                     * чтобы в модели в методе addPartner сохранить это значение в базе данных (поле file)
                     */
                    $this->request->post['file'] = $destination;
                    // var_dump($destination);die;
                }

                $this->model_catalog_partnership->addPartner($this->request->post);
                $data['success'] = 'Спасибо ' . $this->request->post['name'] . '.' . $this->language->get('text_success');


                setcookie("success_name", $this->request->post['name'], time() + 1);


                /**
                 * 2. При успешной отправке данных с формы на странице information/partnership данные
                 * с формы отправлять в письме на служебный email магазина
                 * Запишем данные в формате csv в файле
                 */

                $csv_file = fopen('test.csv', 'w');
                fputcsv($csv_file, $this->request->post, ';');
                fclose($csv_file);


                $mail = new Mail(/*$this->config->get('config_mail_engine')*/);
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                $mail->setTo($this->config->get('config_email'));
                $mail->setFrom($this->config->get('config_email'));
                $mail->setReplyTo($this->request->post['email']);
                $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
                $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
                $mail->setText($this->request->post['enquiry']);
                $mail->send();

               // var_dump($mail);

                //setcookie("success_form", $data['success'], time() + 2592000); // срок действия 30 дней

            }
        }

        /**
         * Формируем ссылку на этот же конроллер; передаем в атрибут action в форме во view
         */
        $data['action'] = $this->url->link('information/partnership');

        /**
         * Лейблы полей и кнопок формы
         */

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_tax_form'] = $this->language->get('entry_tax_form');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_age'] = $this->language->get('entry_age');
        $data['entry_file'] = $this->language->get('entry_file');
        $data['button_send'] = $this->language->get('button_send');
        /**
         * Проверяем есть ли в массиве post значения полей, если да ,
         * то присваиваем их в переменные,
         * а эти переменные выводим во view в элементах формы
         */
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } else {
            $data['name'] = '';
        }
        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }
        if (isset($this->request->post['company'])) {
            $data['company'] = $this->request->post['company'];
        } else {
            $data['company'] = '';
        }
        if (isset($this->request->post['tax_form'])) {
            $data['tax_form'] = $this->request->post['tax_form'];
        } else {
            $data['tax_form'] = '';
        }
        if (isset($this->request->post['age'])) {
            $data['age'] = $this->request->post['age'];
        } else {
            $data['age'] = '';
        }
        if (isset($this->request->post['comment'])) {
            $data['comment'] = $this->request->post['comment'];
        } else {
            $data['comment'] = '';
        }

        /**
         * Текст о партнерстве из настроек
         */
        $data['partnership_text'] = $this->config->get('config_partnership_text');

        /**
         * Перечень доступных форм налогообложения из админки
         */
        $data['partnership_tax'] = explode(',', $this->config->get('config_partnership_tax'));


        /**
         * Формируем ответ браузеру
         * вызываем метод setOutput объекта Response
         * и передаем в него сформированный шаблон
         */
        $this->response->setOutput($this->load->view('information/partnership', $data));

        /**
         * Подключаем футер
         */
        $data['footer'] = $this->load->controller('common/footer');
        // var_dump($data);die;
        /**
         * Подключаем хедер
         */
        $data['header'] = $this->load->controller('common/header');

        /**
         * Формируем ответ браузеру
         * вызываем метод setOutput объекта Response
         * и передаем в него сформированный шаблон
         */
        $this->response->setOutput($this->load->view('information/partnership', $data));

    }
}
