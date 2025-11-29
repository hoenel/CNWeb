<?php
// Mảng chứa thông tin các loài hoa
$flowers = [
    [
        'name' => 'Hoa Dạ Yên Thảo',
        'description' => 'Dạ yên thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở. Hoa có thể nở rực rỡ quanh năm, kể cả tiết trời se lạnh của mùa xuân. Dạ yên thảo có nhiều màu sắc như trắng, xanh, đỏ, hồng, tím, vàng… và có thể trồng trong chậu treo nơi cửa sổ hay ban công.',
        'image' => 'da_yen_thao.jpg'
    ],
    [
        'name' => 'Hoa Đồng Tiền',
        'description' => 'Hoa đồng tiền thích hợp để trồng trong mùa xuân và đầu mùa hè, khi mà cường độ ánh sáng chưa quá mạnh. Cây có hoa to, nở rộ rực rỡ, lại khá dễ trồng và chăm sóc nên sẽ là lựa chọn thích hợp của bạn trong tiết trời này.',
        'image' => 'dongtien.jpg'
    ],
    [
        'name' => 'Hoa Giấy',
        'description' => 'Hoa giấy có mặt ở hầu khắp mọi nơi trên đất nước ta, và nở rực quanh năm. Hoa giấy có nhiều màu sắc rực rỡ, tươi sáng, như trắng, xanh, đỏ, hồng, tím, vàng… Đây là loại cây khá dễ trồng, lại vừa có thể trồng trong chậu, vừa có thể trồng để trang trí nhà ở như dàn leo, bonsai… Ngoài ra, hoa giấy còn tượng trưng cho sự trường thọ, sung túc và may mắn.',
        'image' => 'hoagiay.jpg'
    ],
    [
        'name' => 'Hoa Thanh Tú',
        'description' => 'Hoa thanh tú là loại hoa thích hợp trồng trong mùa xuân và đầu mùa hè, khi mà cường độ ánh sáng chưa quá mạnh. Cây có hoa to, nở rộ rực rỡ, lại khá dễ trồng và chăm sóc nên sẽ là lựa chọn thích hợp của bạn trong tiết trời này.',
        'image' => 'thanhtu.jpg'
    ],
    [
        'name' => 'Hoa Đèn Lồng',
        'description' => 'Hoa đèn lồng có nguồn gốc từ Nam Mỹ, là loại cây khá dễ trồng, nở hoa quanh năm, kể cả tiết trời se lạnh của mùa đông. Hoa có form dáng chuông, màu sắc rực rỡ, nhiều màu như đỏ, cam, vàng, hồng… Đây là loại hoa thích hợp trồng trong chậu treo tại ban công, cửa sổ.',
        'image' => 'denlong.jpg'
    ],
    [
        'name' => 'Hoa Cẩm Chướng',
        'description' => 'Hoa cẩm chướng là loại hoa thích hợp trồng vào mùa xuân và đầu mùa hè. Hoa có màu sắc đa dạng, tươi sáng và rất thơm. Cẩm chướng thường được trồng trong chậu hoặc trồng thành luống để trang trí sân vườn.',
        'image' => 'camchuong.jpg'
    ],
    [
        'name' => 'Hoa Cẩm Tú Cầu',
        'description' => 'Cẩm tú cầu là loại cây thích hợp với mùa xuân và mùa hè, khi mà cường độ ánh sáng chưa quá mạnh. Hoa cẩm tú cầu có màu sắc đa dạng như xanh, tím, hồng, trắng… và nở thành từng chùm lớn, rất đẹp mắt. Cây khá dễ trồng và chăm sóc.',
        'image' => 'camtucau.jpg'
    ],
    [
        'name' => 'Hoa Cúc Đại',
        'description' => 'Cúc đại là loại hoa thích hợp trồng quanh năm, đặc biệt là trong mùa xuân. Hoa có màu sắc đa dạng, tươi sáng, nở to và rực rỡ. Cúc đại khá dễ trồng và chăm sóc, thích hợp trồng trong chậu để trang trí nhà ở.',
        'image' => 'cucdai.jpg'
    ],
    [
        'name' => 'Hoa Cúc Lá Nho',
        'description' => 'Cúc lá nho là loại hoa dễ trồng, mọc nhanh, nở hoa quanh năm. Hoa có màu sắc đa dạng như vàng, cam, đỏ, hồng, tím… và thường nở thành từng chùm nhỏ, rất dễ thương. Đây là loại hoa thích hợp trồng trong chậu treo hoặc để bàn.',
        'image' => 'cuclanho.jpg'
    ],
    [
        'name' => 'Hoa Hải Đường',
        'description' => 'Hải đường là loại hoa thích hợp trồng trong mùa xuân, khi thời tiết mát mẻ. Hoa có màu sắc đa dạng, rực rỡ như đỏ, hồng, cam, trắng… Cây khá dễ trồng và chăm sóc, thích hợp trồng trong chậu để trang trí nhà ở.',
        'image' => 'haiduong.jpg'
    ],
    [
        'name' => 'Hoa Sen',
        'description' => 'Sen là loại hoa truyền thống của Việt Nam, thường nở vào mùa hè. Hoa có màu hồng hoặc trắng, rất đẹp và thơm. Sen tượng trưng cho sự thanh cao, thuần khiết. Thích hợp trồng trong ao, chậu nước.',
        'image' => 'sen.jpg'
    ],
    [
        'name' => 'Hoa Dừa Cạn',
        'description' => 'Dừa cạn là loại hoa dễ trồng, sống khỏe, nở hoa quanh năm. Hoa có màu sắc đa dạng như trắng, hồng, đỏ… Đây là loại cây thích hợp trồng trong mùa hè, chịu được nắng nóng tốt. Thích hợp trồng trong chậu hoặc luống.',
        'image' => 'duacan.jpg'
    ],
    [
        'name' => 'Hoa Sứ Quân Tử',
        'description' => 'Sứ quân tử là loại hoa có hương thơm dịu nhẹ, thường nở vào mùa hè. Hoa có màu trắng hoặc hồng nhạt, rất đẹp và thanh lịch. Cây khá dễ trồng, thích hợp trồng trong sân vườn hoặc chậu lớn.',
        'image' => 'suquantu.jpg'
    ],
    [
        'name' => 'Hoa Tường Vi',
        'description' => 'Tường vi là loại hoa hồng leo, thường nở rực rỡ vào mùa xuân và mùa hè. Hoa có màu sắc đa dạng, thường nở thành từng chùm lớn. Cây khá dễ trồng, thích hợp làm giàn leo, hàng rào hoặc trồng trong chậu.',
        'image' => 'tuongvy.jpg'
    ]
];
?>