var config = require("../../../../config.js");
var app=new getApp();

Page({

  data: {
    modalHidden: true,
    border_left:true,
    border_right: true,
    list:[],
    host: config['host'],
    code_url:'',
    isNull:true,
  },

  shareCodeBtn: function (e) {
    //currentTarget
    let code_url = e.currentTarget.dataset.codeurl;

    this.setData({
      modalHidden: false,
      code_url:code_url,
    });
    
  },

  shareCodeConfirm: function () {
    this.setData({
      modalHidden: true,
    });
  },

  //未兑换
  unexchange: function(){

    let that = this;
    that.getOrderCode(that, 1);

    this.setData({ 
      border_left: true,
      border_right: true
     });
  },

  //兑换
  exchange: function () {

    let that = this;
    that.getOrderCode(that, 2);

    this.setData({
      border_left: false,
      border_right: false,
    });
    
  },

  getOrderCode:function(that,status) {
    wx.request({
      url: config["orderCodeUrl"] + "?open_id=" + app.globalData.openId + "&status=" + status,
      method: "get",
      header: { 'Content-Type': 'application/json' },
      success: function (res) {

        console.log(res.data);  

        if (res.data.length)
        {
          that.setData({
             isNull:false,
           });
        }
        else{
          that.setData({
            isNull: true,
          });
        }
        that.setData({
          list: res.data,
        });
      }
    })
  },

  onLoad: function (options){
    let that=this
    this.getOrderCode(that,1);
  },

  //下拉刷新
  onPullDownRefresh: function () {

    let that = this;
    that.getOrderCode(that, 1);
    this.setData({
      border_left: true,
      border_right: true
    });
    wx.stopPullDownRefresh();
  },

})