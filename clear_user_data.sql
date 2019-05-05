truncate table wst_ad_positions;
truncate table wst_ads;

truncate table wst_brands;

truncate table wst_carts;
truncate table wst_cash_draws;
truncate table wst_cat_brands;
truncate table wst_cat_shops;
truncate table wst_friendlinks;
truncate table wst_goods;
truncate table wst_goods_appraises;
truncate table wst_goods_attributes;
truncate table wst_goods_cats;
truncate table wst_goods_scores;
truncate table wst_goods_specs;
truncate table wst_images;

truncate table wst_log_moneys;
truncate table wst_log_operates;
truncate table wst_log_orders;
truncate table wst_log_sms;
truncate table wst_log_staff_logins;
truncate table wst_log_user_logins;

truncate table wst_messages;

truncate table wst_order_complains;
truncate table wst_order_goods;
truncate table wst_order_refunds;

truncate table wst_orderids;
truncate table wst_orders;

truncate table wst_shop_accreds;
truncate table wst_shop_applys;
truncate table wst_shop_cats;
delete from wst_shop_configs where configId>1;

truncate table wst_shop_freights;
truncate table wst_shop_scores;
delete from wst_shops where shopId>1;

truncate table wst_spec_cats;
truncate table wst_spec_items;

delete from wst_staffs where staffId>3;

truncate table wst_user_address;
truncate table wst_user_ranks;
truncate table wst_user_scores;

delete from wst_users where userId>1;