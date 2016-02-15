<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ModelToolSimpleCustom extends Model {
    static $types = array(
        'order'    => 1,
        'customer' => 2,
        'address'  => 3
    );
    
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

    public function loadData($type, $id, $set) {
        $type = !empty(self::$types[$type]) ? self::$types[$type] : 0;

        if (!$type || !$id) {
            return array();
        }

        $query = $this->db->query("SELECT DISTINCT
                                        data
                                    FROM
                                        simple_custom_data
                                    WHERE
                                        object_type = '" . (int)$type . "'
                                    AND
                                        object_id = '" . (int)$id . "'");

        $result = array();

		if ($query->num_rows) {
            $data = unserialize($query->row['data']);

            foreach ($data as $key => $item) {
                if (empty($item['set']) || (!empty($item['set']) && $item['set'] == $set)) {
                    $result[$key] = $item;
                }
            }
        }

        return $result;
    }

    public function updateData($type, $id, $set, $new) {
        $type = !empty(self::$types[$type]) ? self::$types[$type] : 0;

        $query = $this->db->query("SELECT DISTINCT
                                        data
                                    FROM
                                        simple_custom_data
                                    WHERE
                                        object_type = '".(int)$type."'
                                    AND
                                        object_id = '" . (int)$id . "'");

        if ($query->num_rows) {
            $data = unserialize($query->row['data']);

            foreach ($new as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key]['value'] = $value;

                    $text = !is_array($value) ? $value : '';
                    if (($data[$key]['type'] == 'select' || $data[$key]['type'] == 'radio') && !empty($data[$key]['values'][$value])) {
                        $text = $data[$key]['values'][$value];
                    }
                    if (($data[$key]['type'] == 'checkbox') && !empty($data[$key]['values']) && !empty($value) && is_array($data[$key]['values']) && is_array($value)) {
                        $tmp = array();
                        foreach ($value as $v) {
                            if (array_key_exists($v, $data[$key]['values'])) {
                                $tmp[] = $data[$key]['values'][$v];
                            }
                        }
                        $text = implode(', ', $tmp);
                        unset($tmp);
                    }

                    $data[$key]['text'] = $text;
                }
            }

            $this->db->query("UPDATE 
                                simple_custom_data
                            SET
                                data = '".$this->db->escape(serialize($data))."'
                            WHERE
                                object_type = '".(int)$type."'
                            AND
                                object_id = '" . (int)$id . "'");
        }
    }
}
?>