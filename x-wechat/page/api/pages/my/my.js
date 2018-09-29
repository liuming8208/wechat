var config = require("../../../../config.js");
var app=new getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  //常见问题
  questionBtn:function(){
    wx.navigateTo({
      url: '../question/question',
    })
  },

  //我的优化券
  myCodeBtn:function(){
    wx.navigateTo({
      url: '../mycode/mycode',
    })
  },

  //退出微信小程序
  quitBtn:function(){
    wx.showModal({
      title: '提示',
      content: '退出丞心护肤造型小程序?',
      success:res=>{
        if(res.confirm)
        {
          app.globalData.isQuit=true;
          wx.reLaunch({
            url: '/page/component/index',
          }) 
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  }
})