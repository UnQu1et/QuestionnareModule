<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ModelToolSimpleCustom extends Model {

    public function getCustomerField($customer_id, $field) {
        return $this->getField(2, $customer_id, $field, 'customer');
    }

    public function getAddressField($address_id, $field) {
        return $this->getField(3, $address_id, $field, 'address');
    }

    public function getOrderField($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'order');
    }

    public function getPaymentAddressField($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'payment_address');
    }

    public function getShippingAddressField($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'shipping_address');
    }

    public function getCustomerFieldLabel($customer_id, $field) {
        return $this->getField(2, $customer_id, $field, 'customer', true);
    }

    public function getAddressFieldLabel($address_id, $field) {
        return $this->getField(3, $address_id, $field, 'address', true);
    }

    public function getOrderFieldLabel($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'order', true);
    }

    public function getPaymentAddressFieldLabel($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'payment_address', true);
    }

    public function getShippingAddressFieldLabel($order_id, $field) {
        return $this->getField(1, $order_id, $field, 'shipping_address', true);
    }

    private function getField($type, $id, $field, $set, $label = false) {
        if (!$id || !$field) {
            return '';
        }

        $query = $this->db->query("SELECT DISTINCT
                                        data
                                    FROM
                                        simple_custom_data
                                    WHERE
                                        object_type = '" . (int)$type . "'
                                    AND
                                        object_id = '" . (int)$id . "'");

        if ($query->num_rows) {
            $data = unserialize($query->row['data']);

            $field_id = $field;
            if ($set == 'payment_address') {
                $field_id = 'payment_'.$field;
            } elseif ($set == 'shipping_address') {
                $field_id = 'shipping_'.$field;
            }

            if (!$label) {
                if (isset($data[$field_id]['text'])) {
                    return $data[$field_id]['text'];
                }
            } else {
                if (isset($data[$field_id]['label'])) {
                    return $data[$field_id]['label'];
                }
            }

            foreach ($data as $key => $field_info) {
                if ($field_info['set'] == 'order' && $field_info['field_id'] == $field) {
                    if (!$label) {
                        if (isset($field_info['text'])) {
                            return $field_info['text'];
                        }
                    } else {
                        if (isset($field_info['label'])) {
                            return $field_info['label'];
                        }
                    }
                }
            } 
        }

        return '';
    }
}
?>