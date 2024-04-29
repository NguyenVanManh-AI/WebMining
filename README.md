# WebMining


Tracking 
    + Click 
        + Click sản phẩm ở trang dashboard (care)
        + Click sản phẩm gợi ý ở trang product detail (care)
        => Những sản phẩm được click thì id sản phẩm được thêm vào recent_care

    + Add 
        + Những product được thêm vào giỏ hàng hiện tại thì id click của chúng sẽ được chuyển sang lưu ở recent_add
        + Những product được xóa ra khỏi giỏ hàng hiện tại thì id care của chúng được chuyển về lại id click 
        + Chỉ lưu danh sách id của các product hiện tại đang nằm trong giỏ hàng (không lưu lịch sử trước đó, xử lí add và remove thì như đã nói ở trên)

    + Buy
        + Những sản phẩm được mua (đã được xác nhận hay chưa được xác nhận bởi admin) điều chuyển id của chúng từ recent_add sang recent_buy 
        + Những sản phẩm được mua lần đầu tiên mà TỰ HỦY BỞI NGƯỜI DÙNG thì chuyển id của chúng sang click (vì chúng chưa được nằm trong lịch sử mua hàng)
        + Những sản phẩm được mua mà TỰ HỦY BỞI NGƯỜI DÙNG (trước đó đã được mua thành công hoặc có thể bị HỦY BỞI ADMIN) vẫn được lưu vào recent_buy
        + Những sản phẩm được mua mà bị HỦY BỞI ADMIN vẫn được lưu vào recent_buy vì chứng tỏ người dùng vẫn muốn mua nó (KHÔNG CẦN LÀM GÌ CẢ)
        + Cả admin hủy và customer hủy điều chỉ có 1 cái là : 'order_status' => 0 
        + Customer hủy thì tìm những cái customer_order có status = 1 thì mới tính (không cần xét đến = 0 dù đó là admin hủy hay customer hủy)

Rule  
    + id | id_user | recent_care | recent_add | recent_buy
    + recent_care, recent_add, recent_buy là unique với mỗi id_user
    + id product đã có trong recent_buy thì không cần có trong recent_add , recent_care
    + id product đã có trong recent_add thì không cần có trong recent_care

