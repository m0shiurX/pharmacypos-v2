<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute গ্রহণ করতে হবে।',
    'accepted_if' => ':other যখন :value হয় তখন :attribute গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL নয়।',
    'after' => ':attribute অবশ্যই :date এর পরের তারিখ হতে হবে।',
    'after_or_equal' => ':attribute অবশ্যই :date এর পরের বা সমান তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর থাকতে পারে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা থাকতে পারে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'ascii' => ':attribute শুধুমাত্র এক-বাইটের বর্ণমালা সংখ্যাসূচক অক্ষর এবং চিহ্ন থাকতে পারে।',
    'before' => ':attribute অবশ্যই :date এর আগের তারিখ হতে হবে।',
    'before_or_equal' => ':attribute অবশ্যই :date এর আগের বা সমান তারিখ হতে হবে।',
    'between' => [
        'array' => ':attribute এর :min এবং :max এর মধ্যে আইটেম থাকতে হবে।',
        'file' => ':attribute :min এবং :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute :min এবং :max এর মধ্যে হতে হবে।',
        'string' => ':attribute :min এবং :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মিলছে না।',
    'current_password' => 'পাসওয়ার্ড ভুল।',
    'date' => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals' => ':attribute অবশ্যই :date এর সমান তারিখ হতে হবে।',
    'date_format' => ':attribute :format ফরম্যাটের সাথে মিলছে না।',
    'decimal' => ':attribute এর :decimal দশমিক স্থান থাকতে হবে।',
    'declined' => ':attribute প্রত্যাখ্যান করতে হবে।',
    'declined_if' => ':other যখন :value হয় তখন :attribute প্রত্যাখ্যান করতে হবে।',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute :digits সংখ্যার হতে হবে।',
    'digits_between' => ':attribute :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute এর অবৈধ ছবির মাত্রা রয়েছে।',
    'distinct' => ':attribute ক্ষেত্রটিতে একটি ডুপ্লিকেট মান রয়েছে।',
    'doesnt_end_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শেষ হতে পারে না: :values।',
    'doesnt_start_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শুরু হতে পারে না: :values।',
    'email' => ':attribute একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শেষ হতে হবে: :values।',
    'enum' => 'নির্বাচিত :attribute অবৈধ।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ক্ষেত্রটিতে একটি মান থাকতে হবে।',
    'gt' => [
        'array' => ':attribute এর :value এর বেশি আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের বেশি হতে হবে।',
        'numeric' => ':attribute :value এর বেশি হতে হবে।',
        'string' => ':attribute :value অক্ষরের বেশি হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute এর :value আইটেম বা তার বেশি থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের সমান বা বেশি হতে হবে।',
        'numeric' => ':attribute :value এর সমান বা বেশি হতে হবে।',
        'string' => ':attribute :value অক্ষরের সমান বা বেশি হতে হবে।',
    ],
    'image' => ':attribute একটি ছবি হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ক্ষেত্রটি :other এ বিদ্যমান নেই।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lowercase' => ':attribute ছোট হাতের অক্ষরে হতে হবে।',
    'lt' => [
        'array' => ':attribute এর :value এর কম আইটেম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের কম হতে হবে।',
        'numeric' => ':attribute :value এর কম হতে হবে।',
        'string' => ':attribute :value অক্ষরের কম হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute এর :value এর বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :value কিলোবাইটের সমান বা কম হতে হবে।',
        'numeric' => ':attribute :value এর সমান বা কম হতে হবে।',
        'string' => ':attribute :value অক্ষরের সমান বা কম হতে হবে।',
    ],
    'mac_address' => ':attribute একটি বৈধ MAC ঠিকানা হতে হবে।',
    'max' => [
        'array' => ':attribute এর :max এর বেশি আইটেম থাকতে পারবে না।',
        'file' => ':attribute :max কিলোবাইটের বেশি হতে পারবে না।',
        'numeric' => ':attribute :max এর বেশি হতে পারবে না।',
        'string' => ':attribute :max অক্ষরের বেশি হতে পারবে না।',
    ],
    'max_digits' => ':attribute এর :max এর বেশি সংখ্যা থাকতে পারবে না।',
    'mimes' => ':attribute :values টাইপের একটি ফাইল হতে হবে।',
    'mimetypes' => ':attribute :values টাইপের একটি ফাইল হতে হবে।',
    'min' => [
        'array' => ':attribute এর কমপক্ষে :min আইটেম থাকতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute কমপক্ষে :min হতে হবে।',
        'string' => ':attribute কমপক্ষে :min অক্ষর হতে হবে।',
    ],
    'min_digits' => ':attribute এর কমপক্ষে :min সংখ্যা থাকতে হবে।',
    'multiple_of' => ':attribute :value এর গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফরম্যাট অবৈধ।',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute এ কমপক্ষে একটি অক্ষর থাকতে হবে।',
        'mixed' => ':attribute এ কমপক্ষে একটি বড় হাতের এবং একটি ছোট হাতের অক্ষর থাকতে হবে।',
        'numbers' => ':attribute এ কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute এ কমপক্ষে একটি চিহ্ন থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডেটা লিক এ দেখা গেছে। দয়া করে একটি ভিন্ন :attribute বেছে নিন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'prohibited' => ':attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_if' => ':other যখন :value হয় তখন :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_unless' => ':attribute ক্ষেত্রটি নিষিদ্ধ যদি না :other :values এ থাকে।',
    'prohibits' => ':attribute ক্ষেত্রটি :other কে উপস্থিত থাকতে নিষেধ করে।',
    'regex' => ':attribute ফরম্যাট অবৈধ।',
    'required' => ':attribute ক্ষেত্রটি প্রয়োজন।',
    'required_array_keys' => ':attribute ক্ষেত্রটিতে অবশ্যই নিম্নলিখিতগুলির জন্য এন্ট্রি থাকতে হবে: :values।',
    'required_if' => ':other যখন :value হয় তখন :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_if_accepted' => ':other যখন গৃহীত হয় তখন :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_unless' => ':attribute ক্ষেত্রটি প্রয়োজন যদি না :other :values এ থাকে।',
    'required_with' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_with_all' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_without' => ':values উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_without_all' => ':values এর কোনটিই উপস্থিত না থাকলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'same' => ':attribute এবং :other মিলতে হবে।',
    'size' => [
        'array' => ':attribute এ :size আইটেম থাকতে হবে।',
        'file' => ':attribute :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute :size হতে হবে।',
        'string' => ':attribute :size অক্ষর হতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিতগুলির একটির সাথে শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique' => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ।',
    'uppercase' => ':attribute বড় হাতের অক্ষরে হতে হবে।',
    'url' => ':attribute একটি বৈধ URL হতে হবে।',
    'ulid' => ':attribute একটি বৈধ ULID হতে হবে।',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    'custom-messages' => [
        'quantity_not_available' => 'পরিমাণ :qty :unit উপলব্ধ',
        'this_field_is_required' => 'এই ক্ষেত্রটি প্রয়োজন',
    ],

];
