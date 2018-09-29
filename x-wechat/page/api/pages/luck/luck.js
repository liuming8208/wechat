var config = require("../../../../config.js");
var app = new getApp();

var timer;
var n = 1; //旋转圈数
var degs= [60, 120, 180, 240, 300, 360];// 定义旋转度数
var whichdegs = "";

Page({

  /**
   * 页面的初始数据
   */
  data: {
    animationData: {}, //动画
    hiddenModal:true,
    detail: "恭喜您获得",//弹框内容
    luck: [], //中奖文本名称
    rate: [], //中奖率范围值

    isClick: false,//当前是否已经抽奖
    isCanClick: false, //是否能点击抽奖
    isNotGetUserInfo: true //是否已经获取用户信息
  },

  //开始抽奖
  start: function() {
    let that = this

    if (that.data.isCanClick) {
      if(that.data.isClick==false){

        let n = 1;
        timer = setInterval(function () {
          star.call(that); //开始旋转
          n++;
        }, 300);

        //启动动画
        function star() {
          //开始旋转动画
          var animation = wx.createAnimation({
            transformOrigin: "50% 50%",
            duration: 300,
            timingFunction: "linear"
          });
          animation.rotate(360 * n).step();
          this.setData({
            animationData: animation.export(),
            isClick:true,
          })
        }

        setTimeout(that.stop, 3000);//多久以后自动停止
      }
      else{
          wx.showToast({
            title: '你已抽过奖了',
        })
      }
    }
    else{
      wx.showToast({
        title: '请先点击登录',
      })
    }

  },

  stop: function (e) {
    var that = this;
    clearInterval(timer);
    timer = null;
    //结束动画
    sto.call(that);

    function sto() {
      let rand = parseInt(Math.random() * 100, 10) + 1; //算概率
      let range = that.getRange(rand);  //奖品等级
      whichdegs = that.getValue(that,range);
  
      var animation = wx.createAnimation({
        transformOrigin: "50% 50%",
        duration: 4 * 300 + whichdegs * 1.4,
        timingFunction: "ease-out"
      });

      animation.rotate(n * 360 + whichdegs).step();
      this.setData({
        animationData: animation.export()
      })
    };

    //保存获奖信息
    wx.request({
     url: config['saveLuckTaskUrl'] + "?task_name=" + that.data.detail + "&open_id=" + app.globalData.openId,
     success: res => { }
    })

    //显示弹出框
    timer = setTimeout(function () {
      that.setData({
        hiddenModal: false,
        detail: that.data.detail,
      })
    }, 4 * 300 + whichdegs * 1.4);
  },

  //获奖确定
  listenerConfirm: function (e) {
    var that=this;
    this.setData({
      hiddenModal: true,
    })
    that.reset(); //重置
    wx.reLaunch({
     url: '/page/component/index',
    })
  },

  //重置动画
  reset: function () {
    var animation = wx.createAnimation({
      transformOrigin: "50% 50%",
      duration: 0,
      timingFunction: "linear"
    });
    animation.rotate(0).step();
    this.setData({
      animationData: animation.export()
    })
  },

  //对应旋转的奖品名称
  getValue: function (that,range) {

    let result = degs[1];
    if (range == 1) {
      result = degs[2];
      that.data.detail =that.data.luck[0];
    } 
    else if (range == 2) {
      result = degs[5];
      that.data.detail = that.data.luck[1];
    } 
    else if (range == 3) {
      result = degs[4];
      that.data.detail = that.data.luck[2];
    } 
    else if (range == 4) {
      result = degs[0];
      that.data.detail = that.data.luck[3];
    } 
    else if (range == 5) {
      result = degs[3];
      that.data.detail = that.data.luck[4];
    } 
    else {
      result = degs[1];
      that.data.detail = that.data.luck[5];
    }

    return result;
  },

  //对应奖品等级
  getRange: function(rand) {
    let arr = this.data.rate;
    let range = 6;
    if (arr) {
      let r = 0;
      for (let i in arr) {
        r += parseInt(arr[i]);
        if (rand <= r) {
          range = parseInt(i) + 1;
          break;
        }
      }
    }
    return range;
  },

  //获奖名单
  getLuckList: function(that) {

    wx.request({
      url: config['getLastLuckTaskUrl'],
      success: res => {
        if (res.data.status == 0 && res.data.row != null) {
          that.setData({
            list: res.data.row
          });
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    let that = this;

    wx.request({
      url: config['getLuckTaskNameUrl'] + "?type=1",
      success: res => {

        let arr = []; //文本
        let arr1 = []; //中奖率 

        if (res.data.status == 0 && res.data.row != null) {
          for (let i in res.data.row) {
            arr.push(res.data.row[i].name);
            arr1.push(res.data.row[i].rate);
          }
        }

        that.setData({ luck: arr, rate: arr1 });
      }
    })

    that.getLuckList(that);
    //判断是否已经获取了用户信息
    if (app.globalData.userInfo==null){
      that.setData({ isCanClick: false, isNotGetUserInfo:true })
    }
    else{
      that.setData({ isCanClick: true, isNotGetUserInfo:false })
    }

  },

  //获取用户信息
  getUserInfoBtn: function() {
    let that = this

    if (app.globalData.userInfo) {
      that.saveCustomer(that, app.globalData.userInfo);
    } else {
      wx.getSetting({
        success: res => {
          if (res.authSetting['scope.userInfo']) {
            // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
            wx.getUserInfo({
              lang: "zh_CN",
              success: res => {
                app.globalData.userInfo = res.userInfo
                that.saveCustomer(that, res.userInfo);
              }
            })
          }
        }
      })
    }
  },

  //保存用户信息
  saveCustomer: function(that, userInfo) {
    //获取授权后的用户信息
    wx.request({
      url: config['addCustomerUrl'] + "?open_id=" + app.globalData.openId + '&nick_name=' + userInfo.nickName + '&gender=' + userInfo.gender + '&avatar_url=' + userInfo.avatarUrl + '&city=' + userInfo.city + '&province=' + userInfo.province + '&country=' + userInfo.country,
      headers: {
        'Content-Type': 'application/json'
      },
      method: "GET",
      success: function(res) {
        if (res.data.status == 0) {
          that.setData({
            isCanClick: true,
            isNotGetUserInfo: false
          });
          wx.showToast({
            title: '登录成功，可以抽奖了',
          })
        } else {
          that.setData({
            isCanClick: false,
            isNotGetUserInfo: true
          });
        }
      }
    })
  },

})