
// 请求域名配置
var host = "";

var config = {
  host,
  openIdUrl: `${host}/api/api/open-id`,
  bannerUrl: `${host}/api/api/banner`, 
  taskOrderUrl: `${host}/api/api/task-order`, 
  AddShareTaskUrl: `${host}/api/api/add-share-task`, 
  questionUrl: `${host}/api/api/question`, 
  shopUrl: `${host}/api/api/shop`, 
  shopDetailUrl: `${host}/api/api/shop-detail`, 
  orderCodeUrl: `${host}/api/api/order-code`, 
  getShareTaskUrl: `${host}/api/api/get-share-task`, 
  addCustomerUrl: `${host}/api/api/add-customer`, 
  clickShareTaskUrl: `${host}/api/api/click-share-task`, 
  saveFriendImageUrl: `${host}/api/api/save-friend-image`, 
  getLuckTaskNameUrl: `${host}/api/api/get-task-name`, 
  saveLuckTaskUrl: `${host}/api/api/save-luck-task`, 
  getLastLuckTaskUrl: `${host}/api/api/get-last-luck-task`, 
};

module.exports = config
