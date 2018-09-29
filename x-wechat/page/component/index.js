var config = require("../../config.js");
var app=new getApp();

Page({

  data: {
    host:config['host'],
    banner:[],
    taskOrder:[],
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    interval: 2000,
    duration: 500,
    current:0,
    
    modalHidden: false, //分享模块
    luckHidden: false,    //抽奖模块(隐藏)

    taskId:0, //分享订单Id
    taskName:"", //分享任务名称
  },

  //banner图片浏览
  imageBrowseBtn:function(e){
    let src = e.currentTarget.dataset.src;//获取data-src

    var imgArr = [];
    var objkeys = Object.keys(this.data.banner);
        for (let i = 0; i < objkeys.length; i++){
          let url = this.data.host + this.data.banner[i].url;
          imgArr.push(url);
        } 

        //图片预览
        wx.previewImage({
             current: src, // 当前显示图片的http链接
             urls: imgArr  // 需要预览的图片http链接列表
        })
  },

  //抽奖跳转页
  navigatorBtn:function(){
    wx.navigateTo({
      url: '/page/api/pages/luck/luck',
    })
  },

  //领取和分享
  shareBtn:function(e){
    let that = this;
    let task_id = e.currentTarget.dataset.id; //任务id
    let task_name = e.currentTarget.dataset.name; //任务名称
    let type_value = e.currentTarget.dataset.types; //是分享还领取任务

    //是否授权
    wx.getSetting({
      success: res => {
        if (!res.authSetting['scope.userInfo']) {
          wx.authorize({
            scope: 'scope.record',
            success: function () {
              that.addUserTask(that, task_id, task_name, type_value);
            }
          })
        }
        else {
          that.addUserTask(that, task_id, task_name, type_value);
        }
      }
    })
  },
  
  //领取任务
  addUserTask: function (that, task_id, task_name, type_value) {

    //是分享还是领取(0:领取，1:分享)
    if (type_value == 0) {
      wx.request({
        url: config['AddShareTaskUrl'] + "?task_id=" + task_id + "&open_id=" + app.globalData.openId,
        method: "get",
        headers: { 'Content-Type': 'application/json' },
        success: function (res) {
          if (res.data.status == 0) {
            that.getTaskOrder(that);
          }
        }
      })
    };

    that.setData({
      taskId: task_id,
      taskName: task_name,
      modalHidden: true,
    })
  },

  //分享取消
  shareCancel:function(){
    this.setData({
      modalHidden:false,
    });
  },

  //弹框分享
  onShareAppMessage:function(e){

    if(e.from=="button")
    {
      let task_id = e.target.dataset.taskid; //任务id
      let task_name = e.target.dataset.taskname; //任务名称

      return {
        'title': task_name,
        'path': '/page/api/pages/zan/zan?task_id='+ task_id+"&open_id="+app.globalData.openId,
        'desc': "丞心护肤造型",
        'imageUrl': "/image/ima.jpg",
        succes(e) {
          wx.showShareMenu({
            withShareTicket: true,
          })
        }
      };
    }  
  },

  //保存朋友圈图片
  saveFriendImageBtn:function(e){
    let task_id = e.target.dataset.taskid; //任务id

    //获取访问相册的授权
    let is_auth=true;

    wx.getSetting({
      success:res=>{
        if (!res.authSetting['scope.writePhotosAlbum']){
          wx.authorize({
            scope: 'scope.writePhotosAlbum',
            success:res=>{
              is_auth=true;
            },
            fail:res=>{
              is_auth=false;
            }
          })
        }
      }
    })

    if(is_auth==true){

      wx.request({
        url: config['saveFriendImageUrl']+"?task_id="+task_id + "&open_id=" + app.globalData.openId,
        success: res => {
          wx.downloadFile({
            url: config['host'] + "/" + res.data.row,
            success: res => {
              wx.saveImageToPhotosAlbum({
                filePath: res.tempFilePath,
                success:r=>{
                  wx.showToast({
                    title: '保存成功',
                  })
                }
              })
            }
          })
        }
      })

    }
  },

  //初始化页面
  onLoad:function(){

    //退出
    if(app.globalData.isQuit==true){
      //wx.navigateBack({
        //delta:-1,
      //});
    }

    var that = this;
    app.getOpenId().then(function (res) {
      if (res.status == 200) {
        wx.request({
          url: config["bannerUrl"],
          method: "get",
          headers: { 'Content-Type': 'application/json' },
          success: function (res) {
            that.setData({ banner: res.data });
          }
        });

        that.isUseLuck(that);
        that.getTaskOrder(that);
      }
    });
  },

  //下拉刷新
  onPullDownRefresh:function(){

    let that=this;
    
    that.isUseLuck(that);
    that.getTaskOrder(that);

    wx.stopPullDownRefresh();
  },

  //获取任务(领取任务订单)列表
  getTaskOrder: function (that){
    wx.request({
      url: config["taskOrderUrl"] + "?open_id=" + app.globalData.openId,
      method: "get",
      headers: { 'Content-Type': 'application/json' },
      success: function (res) {
        that.setData({ taskOrder: res.data });
      }
    })
  },

  //是否抽过奖
  isUseLuck: function (that){
    wx.request({
      url: config['getLastLuckTaskUrl'] + "?open_id=" + app.globalData.openId,
      success:res=>{
        if (res.data.row == null || res.data.row.length<=0)
        {
          if(app.globalData.isCloseLuck==false)
          {
            that.setData({ luckHidden: true,});
          }
        }
      }
    })
  },

  //关闭抽奖
  closeLuckBtn: function(){
    app.globalData.isCloseLuck=true;
    this.setData({ luckHidden: false,});
    
  },

})

