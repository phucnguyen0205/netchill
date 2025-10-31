<?php

return [

    'accepted' => ':attribute phải được chấp nhận.',
    'active_url' => ':attribute không phải là URL hợp lệ.',
    'after' => ':attribute phải là ngày sau :date.',
    'after_or_equal' => ':attribute phải là ngày sau hoặc bằng :date.',
    'alpha' => ':attribute chỉ được chứa chữ cái.',
    'alpha_dash' => ':attribute chỉ được chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num' => ':attribute chỉ được chứa chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'before' => ':attribute phải là ngày trước :date.',
    'before_or_equal' => ':attribute phải là ngày trước hoặc bằng :date.',

    'between' => [
        'numeric' => ':attribute phải nằm trong khoảng :min - :max.',
        'file'    => ':attribute phải từ :min - :max kilobytes.',
        'string'  => ':attribute phải từ :min - :max ký tự.',
        'array'   => ':attribute phải có từ :min - :max phần tử.',
    ],

    'boolean' => 'Trường :attribute chỉ được true hoặc false.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'current_password' => 'Mật khẩu hiện tại không chính xác.',

    'email' => ':attribute phải là địa chỉ email hợp lệ.',
    'exists' => ':attribute được chọn không hợp lệ.',
    'file' => ':attribute phải là tệp.',
    'filled' => ':attribute không được để trống.',
    'image' => ':attribute phải là hình ảnh.',
    'integer' => ':attribute phải là số nguyên.',
    'ip' => ':attribute phải là địa chỉ IP hợp lệ.',

    'max' => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max kilobytes.',
        'string'  => ':attribute không được dài hơn :max ký tự.',
        'array'   => ':attribute không được có nhiều hơn :max phần tử.',
    ],

    'min' => [
        'numeric' => ':attribute phải tối thiểu :min.',
        'file'    => ':attribute phải tối thiểu :min kilobytes.',
        'string'  => ':attribute phải tối thiểu :min ký tự.',
        'array'   => ':attribute phải có ít nhất :min phần tử.',
    ],

    'not_in' => ':attribute được chọn không hợp lệ.',
    'numeric' => ':attribute phải là số.',

    'password' => [
        'letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
        'mixed' => 'Mật khẩu phải có cả chữ hoa và chữ thường.',
        'numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
        'symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
        'uncompromised' => 'Mật khẩu đã bị rò rỉ. Vui lòng chọn mật khẩu khác.',
    ],

    'required' => ':attribute là bắt buộc.',
    'same' => ':attribute và :other phải giống nhau.',
    'size' => [
        'numeric' => ':attribute phải bằng :size.',
        'file'    => ':attribute phải có dung lượng :size kilobytes.',
        'string'  => ':attribute phải có :size ký tự.',
        'array'   => ':attribute phải chứa :size phần tử.',
    ],

    'string' => ':attribute phải là chuỗi.',
    'unique' => ':attribute đã tồn tại.',
    'uploaded' => 'Tải lên :attribute thất bại.',
    'url' => ':attribute không phải là URL hợp lệ.',

    'attributes' => [],

];
