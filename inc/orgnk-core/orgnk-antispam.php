<?php

/**
 * Anti-spam measures for Gravity Forms.
 *
 * This will check Gravity Form submissions for common spam characteristics and mark these emails as spam.
 *
 * @param bool $isSpam
 * @param array $form
 * @param array $entry
 * @return bool
 */
function orgnk_antispam($isSpam, $form, $entry)
{
    if (true === $isSpam) {
        return true;
    }

    $emptyEntry = true;
    $spam = 0;

    foreach ($form['fields'] as $field) {
        // Skip admin fields
        if ($field->is_administrative()) {
            continue;
        }

        $value = trim($field->get_value_export($entry));
        if (!empty($value)) {
            $emptyEntry = false;
        }

        // Check name fields are not URLs (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'name'
                || strpos(strtolower($field->label), 'name') !== false
            )
        ) {
            if (preg_match('/((http[s]?:)|(www\.)|(\.com))/i', $value)) {
                $spam += 100;
            }
        }

        // Check name, email and phone fields for ZAP - seems to be a spam bot (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'name'
                || strpos(strtolower($field->label), 'name') !== false
                || $field->get_input_type() === 'email'
                || strpos(strtolower($field->label), 'email') !== false
                || $field->get_input_type() === 'phone'
                || strpos(strtolower($field->label), 'phone') !== false
            )
        ) {
            if (preg_match('/^\s*ZAP\s*$/i', $value)) {
                $spam += 100;
            }
        }

        // Check email fields do not contain Russian email addresses (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'email'
                || strpos(strtolower($field->label), 'email') !== false
            )
        ) {
            if (preg_match('/@[^.]*\.ru/i', $value)) {
                $spam += 100;
            }
        }

        // Check email fields do not contain "no-reply" email addresses (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'email'
                || strpos(strtolower($field->label), 'email') !== false
            )
        ) {
            if (preg_match('/^(.*?)no[\-]?reply(.*?)@/i', $value)) {
                $spam += 100;
            }
        }

        // Check phone numbers do not start with a 1-3, 5 or 7-9, only contain numbers and contain more than 9 extra numbers (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'phone'
                || strpos(strtolower($field->label), 'phone') !== false
                || strpos(strtolower($field->label), 'mobile') !== false
            )
        ) {
            if (preg_match('/^([1-3]|5|[7-9])[0-9]{9}/', $value)) {
                $spam += 100;
            }
        }

            // Check phone numbers do not start with 555 (certain spam)
        if (
            !empty($value)
            && (
                $field->get_input_type() === 'phone'
                || strpos(strtolower($field->label), 'phone') !== false
                || strpos(strtolower($field->label), 'mobile') !== false
            )
        ) {
            if (preg_match('/^555/', $value)) {
                $spam += 100;
            }
        }

        // Check all other fields do not contain certain HTML tags (certain spam)
        if (
            !empty($value)
            && (
                in_array($field->get_input_type(), [
                    'hidden',
                    'text',
                    'textarea',
                ])
            )
        ) {
            if (preg_match('/<\/?[a|img|iframe|script|link][\s>]/ims', $value)) {
                $spam += 100;
            }
        }

        // Check all other fields do not contain Russian (cyrillic) characters (likely spam)
        if (
            !empty($value)
            && (
                in_array($field->get_input_type(), [
                    'hidden',
                    'text',
                    'textarea',
                ])
            )
        ) {
            if (preg_match('/[А-Яа-яЁё]/u', $value)) {
                $spam += 66;
            }
        }

        // Check all other fields, if they contain URLs, they're likely spam
        if (
            !empty($value)
            && (
                in_array($field->get_input_type(), [
                    'hidden',
                    'text',
                    'textarea',
                ])
            )
        ) {
            preg_match_all('/(http[s]?|ftp|mailto|phone):\/\//i', $value, $matches, PREG_SET_ORDER);
            $spam += (33 * count($matches));
        }

        // Check all other fields, if they contain certain trigger words (SEO, ranking, development, etc.), they may be spam
        if (
            !empty($value)
            && (
                in_array($field->get_input_type(), [
                    'hidden',
                    'text',
                    'textarea',
                ])
            )
        ) {
            preg_match_all('/(seo|ranking|development|freelancer|first page|optimisation|optimization|optimised|optimized|pussy|sex|dick|erection|hot chicks|fuck|web dev|website dev|greetings)/i', $value, $matches, PREG_SET_ORDER);
            $spam += (20 * count($matches));
        }
    }

    // If we've gone through all fields and no value has been filled in, then it's spam
    if ($emptyEntry) {
        $spam += 100;
    }

    return ($spam >= 100);
}

add_filter( 'gform_entry_is_spam', 'orgnk_antispam', 11, 3 );
