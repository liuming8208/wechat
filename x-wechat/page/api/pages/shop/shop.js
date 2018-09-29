var config = require("../../../../config.js");
var app=new getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    host: config['host'],
    show: false,
    shop_list: [],
    index: 0,
    display:'none', 
    detail:[],
  },

  //banner图片浏览
  imageBrowseBtn: function (e) {
    let src = e.currentTarget.dataset.src;//获取data-src

    var imgArr = [];
    var objkeys = Object.keys(this.data.detail);
    let url = this.data.host + this.data.detail.url;

        imgArr.push(url);
        
        //图片预览
        wx.previewImage({
          current: src, // 当前显示图片的http链接
          urls: imgArr  // 需要预览的图片http链接列表
        })
  },

  // 点击下拉显示框
  selectTap() {
    this.setData({
      show: !this.data.show,
      display:"",
    });
  },
  // 点击下拉列表
  optionTap(e) {
    var that=this;
    let index = e.currentTarget.dataset.index;//获取点击的下拉列表的下标
    var id=e.currentTarget.dataset.id; //门店id 
    this.setData({
      index: index,
      show: !this.data.show,
      display: "none",
    });
    that.getShopDetail(that,id);
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this;
    wx.request({
      url: config["shopUrl"] + "?open_id=" + app.globalData.openId,
      method: "get",
      header: { 'Content-Type': 'application/json' },
      success: function (res)
      {
         that.setData({shop_list:res.data});
      }
     });

    this.getShopDetail(that,0);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    
    let that = this;
    wx.request({
      url: config["shopUrl"] + "?open_id=" + app.globalData.openId,
      method: "get",
      header: { 'Content-Type': 'application/json' },
      success: function (res) {
        that.setData({ shop_list: res.data });
      }
    });

    this.getShopDetail(that, 0);
    wx.stopPullDownRefresh();
  },

  getShopDetail:function(that,id){
    wx.request({
      url: config["shopDetailUrl"] + "?open_id=" + app.globalData.openId+"&id="+id,
      method: "get",
      header: { 'Content-Type': 'application/json' },
      success: function (res) {
        that.setData({
          detail: res.data,
          markers: [{
            latitude: res.data.latitude,
            longitude: res.data.longitude,
            name: res.data.address,
          }], });
      }
    });
  },

  showShopAddress:function(e){
    let that=this;
    let lg = e.currentTarget.dataset.longitude;
    let lt = e.currentTarget.dataset.latitude;
    
    wx.openLocation({
      latitude: Number(lt),
      longitude: Number(lg),
    })
  },

  callPhone:function(e)
  {
    let phone=e.currentTarget.dataset.phone;
    wx.makePhoneCall({
      phoneNumber: phone,
      success:function(){
      }
    })
  }




})