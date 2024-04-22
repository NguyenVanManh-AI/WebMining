# WebMining

Requirement 

user_datas 
id    id_user    recent_care    recent_add    recent_buy

- recent_care, recent_add, recent_buy là unique với mỗi id_user
- 1.Những sản phẩm được click thì id sản phẩm được thêm vào recent_care
- 2.Những sản phẩm ở recent_care được thêm vào giỏ hàng thì chuyển id của chúng sang lưu ở recent_add
- 3.Những sản phẩm ở recent_add được buy thì chuyển id của chúng sang lưu ở recent_buy

- recent_care -> recent_add -> recent_buy

