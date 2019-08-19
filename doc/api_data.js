define({ "api": [
  {
    "type": "get",
    "url": "/company",
    "title": "关键词：公司机构",
    "description": "<p>关键词：公司机构 - 马骏飞</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "industry_code",
            "description": "<p>所属行业类型编码  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型 KC002:公司;KC004:机构</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示条数</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n     \"statusCode\": 4000,\n     \"statusMessage\": \"获取数据成功\",\n     \"responseData\": {\n         \"total\": 37,\n         \"per_page\": 5,\n         \"current_page\": 1,\n         \"last_page\": 8,\n         \"next_page_url\": \"http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2\",\n         \"prev_page_url\": null,\n         \"from\": 1,\n         \"to\": 5,\n         \"data\": [\n             {\n                 \"code\": \"cs8dBG4I9jSz7tj5\", // 关键词code\n                 \"name_cn\": \"Rocket Crafters\",   // 关键词中文名\n                 \"name_en\": \"Rocket Crafters\",   // 关键词英文名\n                 \"category_code\": \"KC002\",   // 关键词类型\n                 \"oneword\": \"火箭技术研发商\",   // 关键词一句话描述\n                 \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495158214.jpg!initial\"  // 关键词缩略图\n             }\n         ]\n     }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/CorevalueController.php",
    "groupTitle": "Content",
    "name": "GetCompany"
  },
  {
    "type": "get",
    "url": "/core",
    "title": "关键词：产科项",
    "description": "<p>关键词：产科项 - 马骏飞</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "industry_code",
            "description": "<p>所属行业类型编码  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型 p:产品;t:科技;s:项目;</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示条数</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n     \"statusCode\": 4000,\n     \"statusMessage\": \"获取数据成功\",\n     \"responseData\": {\n         \"total\": 37,\n         \"per_page\": 5,\n         \"current_page\": 1,\n         \"last_page\": 8,\n         \"next_page_url\": \"http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2\",\n         \"prev_page_url\": null,\n         \"from\": 1,\n         \"to\": 5,\n         \"data\": [\n             {\n                 \"id\": 28,  // 产科项id\n                 \"name\": \"龙飞船\", // 产科项中文名\n                 \"name_en\": \"SpaceX Dragon\", // 产科项英文名\n                 \"thumb_path\": \"http://uploads2.b0.upaiyun.com///prod/prod1499822420.jpg!ptpe.img\", // 产科项缩略图\n                 \"keyword_code\": \"W1UN4YbN4pKnuvR6\", // 关键词code\n                 \"keyword_name\": \"太空探索技术公司\", // 关键词名称\n                 \"keyword_name_en\": \"SpaceX\",    // 关键词英文名\n                 \"keyword_logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial\" // 关键词缩略图\n             }\n         ]\n     }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/CorevalueController.php",
    "groupTitle": "Content",
    "name": "GetCore"
  },
  {
    "type": "get",
    "url": "/keyword/core/{core_id}",
    "title": "具体内容：产科项",
    "description": "<p>具体内容：产科项 ———— 朱朔男</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "core_id",
            "description": "<p>产科项id</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  HTTP/1.1 201 Created\n {\n\t  \"statusCode\": 4000,\n\t  \"statusMessage\": \"获取数据成功\",\n\t  \"responseData\": {\n\t    \"keywords\": {\n\t        \"code\": \"4KZYFMeqNN8PfwcU\",\t\t\t// 关键词code\n\t      \t\"name\": \"Makeblock\",\t\t\t\t// 中文名\n\t      \t\"name_en\": \"Makeblock\",\t\t\t\t// 英文名\n\t\t  \t\"keyword_category_code\": \"KC004\"\t// 关键词类型code\n\t      \t\"oneword\": \"编程机器人设计研发商\",\t// 一句话\n\t      \t\"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial\",\t// 缩略图\n\t      \t\"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial\",\t// 头图\n\t      \t\"birth_info\": \"成立于：1824年\"\t\t// 成立时间\n\t    },\n\t    \"cores\": {\t\t\t\t\t\t\t\t// 产科项详情\n\t        \"id\": 746,\t\t\t\t\t\t\t// 产科项id\n\t        \"name\": \"超音速喷气式客机\",\t\t\t// 中文名\n\t        \"name_en\": \"X-Plane\",\t\t\t\t// 英文名\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///prod/prod1500271562.jpg!ptpe.img\",\t\t// 缩略图\n\t        \"contents\": [\t\t\t\t\t\t// 内容\n\t          \"洛克希德·马丁推出卫星平台型谱方案，是为了让不同型号的卫星能够共用某些通用部件，以便更快、更低成本地完成不同 *用户对于卫星大小、任务及其轨道选项等个性化需求的订单。\",\n\t          \"卫星平台型谱方案一共纳入4种卫星平台，它们分别是LM 50系列平台、LM 400系列平台、LM 1000系列平台和LM 2100系列平台。\",\n\t          \"LM 50系列平台：该平台生产10-100公斤的纳米卫星，主要供美国空军或商业用户开展空间实验。\",\n\t          \"LM 400系列平台：该平台生产140-800公斤的小卫星，且在推进系统上有所改进，以用来执行低地轨道、近地轨道以及星际任务，其成本可比以前降低20%-30%，交货速度则能提高3倍。\",\n\t          \"LM 1000系列平台：该平台生产275-2200公斤的中、大卫星，可以携带较多载荷来执行多种轨道和星际任务。\",\n\t          \"LM 2100系列平台：该平台生产2300公斤左右的大卫星，是在洛克希德·马丁此前的通信卫星系列A 2100的基础上的改进型号，主要以提高功率和灵活性为原则，对包括太阳能电池阵和霍尔推进器在内的26项技术进行了改进。\"\n\t        ]\n\t    }\n\t  }\n\t}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/KeywordController.php",
    "groupTitle": "Content",
    "name": "GetKeywordCoreCore_id"
  },
  {
    "type": "get",
    "url": "/keyword/peopleinfo",
    "title": "具体内容：人物",
    "description": "<p>具体内容：人物------------------------------------王磊</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code（NvXUKMnqdD2FVol6，rwTxFK2VJZDqMcmI，X2IRnGh0fTQscvPZ，c8moPDQfmWAwG48w，u1KD6oY7xuOOFoqN，0TpVeXtynhwTl0fR，wpOpVjZkvAOVtIaS，G97PnKRFlCsueJeH，me01RHZjFW7GGibs）</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n   {\n       \"statusCode\": \"501\",\n       \"statusMessage\": \"非法数据请求\",\n       \"responseData\": {\n          \"keywords\": { // 关键词信息\n           \"code\": \"NvXUKMnqdD2FVol6\", // 关键词code\n           \"name_cn\": \"伊隆·马斯克\", // 关键词中文名\n           \"name_en\": \"Elon Musk\", // 关键词英文名\n           \"category_code\": \"KC003\", // 关键词类型code\n           \"oneword\": \"现任 SpaceX CEO\", // 关键词一句话\n           \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1494409328.jpg!initial\", // 关键词缩略图\n           \"head_path\": \"\", // 关键词头图\n           \"desc\": \"PayPal、OpenAI联合创始人，The Boring Company创始人，现任SpaceX CEO，参与创立Neuralink，是一位连续创业者、第一位提出超级高铁构想者。现任特斯拉CEO、SpaceX CEO、The Boring Company CEO、SolarCity董事会主席。\", // 关键词简介\n           \"birth_info\": \"美国华盛顿,成立于：1971年06月28日\" // 关键词城市，时间信息\n       },\n           \"gallerys\": [ // 关键词图库\n               {\n                   \"id\": 1, // 关键词图库ID\n                   \"title\": \"Solar Probe Plus太阳探测器\", // 关键词图库标题\n                   \"picture\": [ // 关键词图片\n                       {\n                           \"desc\": \"\", // 关键词图片简介\n                           \"thumb_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270\", // 关键词图片缩略图\n                           \"original_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270\" // 关键词图片大图\n                       }\n                   ]\n               },\n           ],\n           \"block\": [ // 关键词信息块\n               {\n                   \"block_id\": 904, // 关键词信息块ID\n                   \"title\": \"业务方向\", // 关键词信息块标题\n                   \"info\": [ // 关键词信息块内容\n                       {\n                           \"content\": \"从1958年始，美国宇航局负责了美国的太空探索，例如登月的阿波罗计划，太空实验室，以及随后的航天飞机。\" // 关键词信息\n                       },\n                       {\n                           \"content\": \"自2006年2月，NASA的愿景是“开拓未来的太空探索，科学发现及航空研究”；使命是“理解并保护我们依赖生存的行星；探索宇宙，找到地球外的生命；启示我们的下一代去探索宇宙”。\"\n                       }\n                   ]\n               }\n           ]\n       }\n   }",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/PeopleController.php",
    "groupTitle": "Content",
    "name": "GetKeywordPeopleinfo"
  },
  {
    "type": "get",
    "url": "/news/details",
    "title": "具体内容：新闻",
    "description": "<p>具体内容：新闻------------------------------王磊</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "draft_id",
            "description": "<p>新闻ID（示例：1,2,3,4,5,6,7）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code（示例：UBDp4pOVRKf0q2wW，svC6eXb8uKQoWULI，A9UzNOhBJyO2PGGM，QujrFl7lIYOxssWM，YO2QEELHzE5YonZC，8sSeNaK0mxkd1fVB，Ezo81Xlf4wdNwe3K）</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n   {\n       \"statusCode\": 4000,\n       \"statusMessage\": \"获取数据成功\",\n       \"responseData\": {\n           \"keywords\": { // 主关键词信息\n               \"code\": \"qZ7i5UH1hMS5prC8\", // 关键词code\n               \"name\": \"拉里·佩奇\",  // 关键词中文名\n               \"name_en\": \"Larry Page\", // 关键词英文名\n               \"oneword\": \"Google联合创始人\", // 关键词一句话描述\n               \"desc\": \"Google公司创始人之一，2011年4月4日任谷歌CEO ，现任Alphabet公司CEO。\", // 关键词简介\n               \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1496801733.jpg!initial\", // 关键词缩略图\n               \"head_path\": \"\" // 关键词头图\n           },\n           \"news\": { // 新闻信息\n               \"id\": 1, // 新闻ID\n               \"title\": \"NextVR领衔\", // 新闻标题\n               \"subtitle\": \"虚拟现实VR直播来袭\", // 新闻副标题\n               \"author\": \"柳威\", // 新闻写稿人\n               \"email\": \"13611086916@xincheng.tv\" // 写稿人邮箱\n           },\n           \"newsintro\": // 新闻概要  \"根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学、李嘉诚基金会、市环保局负责配合做好此项工作，由汕头市环境保护研究所抽调精干专业技术力量协调配合开展科研项目调查采样，协助Nanospun科技有限公司开展科研项目初步研究、资料收集和地点选择等一系列工作；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂\",\n           \"newscontents\": [（新闻内容，\"picture\"-图片,\"gallery\"-图库,\"video\"-视频, 其他全是文字）\n               {\n                   \"id\": 15724, // 新闻内容ID\n                   \"element_type\": \"gallery\", // 新闻内容类型 （图库）\n                   \"element_id\": 1, // 新闻内容类型ID\n                   \"element_content\": { // 新闻内容\n                       \"id\": 1, // 图片ID\n                       \"title\": \"Solar Probe Plus太阳探测器\", // 图片标题\n                       \"pictures\": [ // 图片\n                           {\n                               \"desc\": \"\", //图片简述\n                               \"original_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270\" // 图片url\n                           },\n                           {\n                               \"desc\": \"\",\n                               \"original_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery_p1496485065.jpg\"\n                           }\n                       ]\n                   }\n               },\n               {\n                   \"id\": 15725,  // 新闻内容ID\n                   \"element_type\": \"video\", // 新闻内容类型 （视频）\n                   \"element_id\": 3, // 新闻内容类型ID\n                   \"element_content\": { // 新闻内容\n                       \"title\": \"顺丰视频\", // 视频标题\n                       \"thumb_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery_p1496485065.jpg\", // 视频背景\n                       \"url\": \"XMzA3NDg4NTc2MA\" // 视频播放码\n                   }\n               },\n               {\n                   \"id\": 366,\n                   \"element_type\": \"text\", // 新闻内容类型 （文字）\n                   \"element_id\": 0,\n                   \"element_content\": \"与FACEBOOK旗下的Oculus公司和HTC的VIVE等专注于硬件发展不同的是，来自美国加利福尼亚拉古娜海滩的VR公司 NextVR ，是一家专注于虚拟现实内容产生和制作的公司，准确的来说，就是VR直播。而NextVR的这种定位，与其两位公司创始人的经历是密不可分的。\"\n               },\n               {\n                   \"id\": 12543,\n                   \"element_type\": \"picture\",// 新闻内容类型 （图片）\n                   \"element_id\": 2550,\n                   \"element_content\": {\n                       \"desc\": \"\",\n                       \"original_path\": \"http://uploads2.b0.upaiyun.com///news/news1506477562.jpg\"\n                   }\n               }\n           ],\n           \"key\": [（相关关键词信息）\n               {\n                   \"code\": \"UBDp4pOVRKf0q2wW\", // 相关关键词code\n                   \"name\": \"NextVR\", // 相关关键词中文名\n                   \"name_en\": \"NextVR\", // 相关关键词英文名\n                   \"oneword\": \"VR直播服务商\", // 相关关键词一句话\n                   \"desc\": \"NextVR于2009年在加州创立，是一家VR直播服务商，创始人为David Cole、DJ Roller。现任CEO为Dave Cole。\" // 相关关键词简介\n               }\n           ]\n       }\n   }",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/NewsController.php",
    "groupTitle": "Content",
    "name": "GetNewsDetails"
  },
  {
    "type": "get",
    "url": "/people",
    "title": "关键词：人物",
    "description": "<p>关键词：人物 - 马骏飞</p>",
    "group": "Content",
    "permission": [
      {
        "name": "需要"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "industry_code",
            "description": "<p>所属行业类型编码  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型 KC003:人物</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示条数</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n     \"statusCode\": 4000,\n     \"statusMessage\": \"获取数据成功\",\n     \"responseData\": {\n         \"total\": 37,\n         \"per_page\": 5,\n         \"current_page\": 1,\n         \"last_page\": 8,\n         \"next_page_url\": \"http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2\",\n         \"prev_page_url\": null,\n         \"from\": 1,\n         \"to\": 5,\n         \"data\": [\n             {\n                 \"code\": \"cs8dBG4I9jSz7tj5\", // 关键词code                                                         \n                 \"name_cn\": \"Rocket Crafters\",   // 关键词中文名                                                      \n                 \"name_en\": \"Rocket Crafters\",   // 关键词英文名                                                      \n                 \"category_code\": \"KC002\",   // 关键词类型                                                           \n                 \"oneword\": \"火箭技术研发商\",   // 关键词一句话描述                                                            \n                 \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495158214.jpg!initial\"  // 关键词缩略图  \n                 \"role\": {\n                     \"company\": \"谷歌\",     // 公司\n                     \"role\": \"创始人\"       // 职称\n                 }\n             },\n         ]\n     }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/CorevalueController.php",
    "groupTitle": "Content",
    "name": "GetPeople"
  },
  {
    "type": "get",
    "url": "/keyword/achievement/{keyword_code}",
    "title": "关键词：进展",
    "description": "<p>关键词：进展 ———— 朱朔男</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  HTTP/1.1 201 Created\n {\n\t  \"statusCode\": 4000,\n\t  \"statusMessage\": \"获取数据成功\",\n\t  \"responseData\": {\n\t\t\"keywords\": {\n\t        \"code\": \"4KZYFMeqNN8PfwcU\",\t\t\t// 关键词code\n\t      \t\"name\": \"Makeblock\",\t\t\t\t// 中文名\n\t      \t\"name_en\": \"Makeblock\",\t\t\t\t// 英文名\n\t\t  \t\"keyword_category_code\": \"KC004\"\t// 关键词类型code\n\t      \t\"oneword\": \"编程机器人设计研发商\",\t// 一句话\n\t      \t\"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial\",\t// 缩略图\n\t      \t\"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial\",\t// 头图\n\t      \t\"birth_info\": \"成立于：1824年\"\t\t// 成立时间\n\t    },\n\t    \"project\": [\t\t\t\t\t\t\t// 项目\n\t      {\n\t        \"id\": 1040,\t\t\t\t\t\t\t// 项目id\n\t        \"name\": \"卫星平台型谱方案\",\t\t\t// 中文名\t\t\t\t\t\t\n\t        \"name_en\": \"Family of Satellite Buses\",\t\t\t\t// 英文名\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///pj/pj1506313482.jpg!ptpe.img\",\t\t// 缩略图\n\t        \"content\": [\t\t\t\t\t\t// 信息条\n\t          \"洛克希德·马丁推出卫星平台型谱方案，是为了让不同型号的卫星能够共用某些通用部件，以便更快、更低成本地完成不同 *用户对于卫星大小、任务及其轨道选项等个性化需求的订单。\",\n\t          \"卫星平台型谱方案一共纳入4种卫星平台，它们分别是LM 50系列平台、LM 400系列平台、LM 1000系列平台和LM 2100系列平台。\",\n\t          \"LM 50系列平台：该平台生产10-100公斤的纳米卫星，主要供美国空军或商业用户开展空间实验。\",\n\t          \"LM 400系列平台：该平台生产140-800公斤的小卫星，且在推进系统上有所改进，以用来执行低地轨道、近地轨道以及星际任务，其成本可比以前降低20%-30%，交货速度则能提高3倍。\",\n\t          \"LM 1000系列平台：该平台生产275-2200公斤的中、大卫星，可以携带较多载荷来执行多种轨道和星际任务。\",\n\t          \"LM 2100系列平台：该平台生产2300公斤左右的大卫星，是在洛克希德·马丁此前的通信卫星系列A 2100的基础上的改进型号，主要以提高功率和灵活性为原则，对包括太阳能电池阵和霍尔推进器在内的26项技术进行了改进。\"\n\t        ]\n\t      },\n\t      {\n\t        \"name\": \"小型核聚变反应堆\",\n\t        \"id\": 969,\n\t        \"name_en\": \"\",\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///pj/pj1500271792.jpg!ptpe.img\",\n\t        \"content\": []\n\t      },\n\t      {\n\t        \"name\": \"USC-洛克希德·马丁公司量子计算中心\",\n\t        \"id\": 970,\n\t        \"name_en\": \"\",\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///pj/pj1500272107.jpg!ptpe.img\",\n\t        \"content\": []\n\t      }\n\t    ],\n\t    \"product\": [\t\t\t\t\t\t\t// 产品\n\t      {\n\t        \"name\": \"超音速喷气式客机\",\n\t        \"id\": 509,\n\t        \"name_en\": \"X-Plane\",\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///prod/prod1500271562.jpg!ptpe.img\",\n\t        \"content\": []\n\t      },\n\t      {\n\t        \"name\": \"商用超音速喷气式客机\",\n\t        \"id\": 510,\n\t        \"name_en\": \"N+2\",\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///prod/prod1500271490.jpg!ptpe.img\",\n\t        \"content\": []\n\t      }\n\t    ],\n\t    \"tech\": [\t\t\t\t\t\t\t// 科技\n\t      {\n\t        \"name\": \"3D打印军用卫星部件\",\n\t        \"id\": 820,\n\t        \"name_en\": \"\",\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///tech/tech1500271690.jpg!ptpe.img\",\n\t        \"content\": []\n\t      }\n\t    ]\n\t  }\n\t}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/KeywordController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordAchievementKeyword_code"
  },
  {
    "type": "get",
    "url": "/keyword/block/{keyword_code}",
    "title": "关键词：信息块",
    "description": "<p>关键词信息块 ———— 朱朔男</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n    \"statusCode\": 200,\n    \"statusMessage\": \"获取数据成功\",\n    \"responseData\": {\n        \"keywords\": {\n            \"code\": \"4KZYFMeqNN8PfwcU\",\t\t\t// 关键词code\n      \t\t\"name\": \"Makeblock\",\t\t\t\t// 中文名\n      \t\t\"name_en\": \"Makeblock\",\t\t\t\t// 英文名\n\t  \t\t\"keyword_category_code\": \"KC004\"\t// 关键词类型code\n      \t\t\"oneword\": \"编程机器人设计研发商\",\t// 一句话\n      \t\t\"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial\",\t// 缩略图\n      \t\t\"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial\",\t// 头图\n      \t\t\"birth_info\": \"成立于：1824年\"\t\t// 成立时间\n        },\n        \"blocks\": [\t\t\t\t\t\t\t\t// 关键词信息块\n            {\n                \"id\": 299,\t\t\t\t\t\t// 信息块id\n                \"title\": \"业务方向\",\t\t\t\t// 信息块标题\n                \"contents\": [\t\t\t\t\t// 信息块内容\n\t\t          \"曼彻斯特大学分为生物、医学与健康院系、科学与工程院系和人文院系三个院系，每个院系由多个学院组成。\",\n\t\t          \"学校有三个学院，分别是工程学院、自然科学学院和社会科学和人文艺术学院，学生们可以学习19个专业和18个副修专业。\"\n\t\t        ]\n            }\n        ]\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/KeywordController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordBlockKeyword_code"
  },
  {
    "type": "get",
    "url": "/keyword/gallery/{keyword_code}",
    "title": "关键词：图库",
    "description": "<p>关键词：图库 ———— 朱朔男</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  HTTP/1.1 201 Created\n\t{\n\t  \"statusCode\": 200,\n\t  \"statusMessage\": \"获取数据成功\",\n\t  \"responseData\": {\n\t    \"keywords\": {\t\t\t\t\t\t// 关键词相关信息\n\t\t  \"code\": \"4KZYFMeqNN8PfwcU\",\t\t// 关键词code\n\t      \"name\": \"Makeblock\",\t\t\t\t// 中文名\n\t      \"name_en\": \"Makeblock\",\t\t\t// 英文名\n\t\t  \"keyword_category_code\": \"KC004\"\t// 关键词类型code\n\t      \"oneword\": \"编程机器人设计研发商\",\t// 一句话\n\t      \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial\",\t// 缩略图\n\t      \"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial\",\t// 头图\n\t      \"birth_info\": \"成立于：1824年\"\t\t// 成立时间\n\t    },\n\t    \"gallerys\": [\t\t\t\t\t\t// 图库相关信息\n\t      {\n\t        \"gallery_id\": 24,\t\t\t\t// 图库id\n\t        \"title\": \"Makeblock产品\",\t\t// 标题\n\t        \"picture\": [\t\t\t\t\t// 图库下面的图片\n\t          {\n\t            \"desc\": \"\",\t\t\t\t// 图片描述\n\t        \t\"thumb_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496651891.jpg!news.logo.500.270\",\t// 缩略图\n\t        \t\"original_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496651891.jpg!news.logo.500.270\",\t// 原图\n\t        \t\"is_cover\": 1\t\t\t// 是否为封面（0-否，1-是）\n\t          }\n\t        ],\n\t\t\t\"num\": 10\t\t\t\t\t// 图片数量\n\t      }\n\t    ]\n\t  }\n\t}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/KeywordController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordGalleryKeyword_code"
  },
  {
    "type": "get",
    "url": "/keyword/intro",
    "title": "关键词：简介",
    "description": "<p>关键词：简介 - 马骏飞</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n    \"statusCode\": 200,\n    \"statusMessage\": \"获取数据成功\",\n    \"responseData\": {\n        \"keywords\": {\n                \"code\": \"W1UN4YbN4pKnuvR6\", //关键词code\n                \"name\": \"太空探索技术公司\", // 关键词中文名\n                \"name_en\": \"SpaceX\",    // 关键词英文名\n                \"keyword_category_code\": \"KC002\",   //关键词类型id\n                \"oneword\": \"太空运输服务商\",   // 关键词一句话描述\n                \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial\", // 关键词缩略图\n                \"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1495701352.jpg!news.logo.500.270\", // 关键词头图\n                \"birth_info\": \"成立于：2002年06月\" // 生日信息\n            },\n            \"blocks\": [\n                {\n                    \"id\": 152,  // 信息块id\n                    \"title\": \"失败统计\",    // 信息块标题\n                    \"contents\": [   // 信息条\n                        \"截止2017年5月初，SpaceX在当年的五次发射任务均获成功。\",\n                        \"猎鹰1号共5次发射任务中，有3次失败，直到第四次才首次成功。\",\n                        \"猎鹰9号从2010年6月4日首次任务至2017年5月1日的七年中，共执行了33次任务，有9次失败，其中2016年失败4次。\",\n                        \"在2016年9月的发射任务中，猎鹰9号在发射前例行测试时发生爆炸。\",\n                        \"2017年1月，SpaceX走出爆炸的阴影，进行爆炸后的首次发射。\"\n                    ]\n            }\n        ],\n        \"gallery\": [\n            {\n                \"id\": 1,    // 图库id\n                \"title\": \"Solar Probe Plus太阳探测器\",   // 图库标题\n                \"picture\": [    // 图库里的图片集合\n                    {\n                        \"desc\": \"\", // 图片描述\n                        \"thumb_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270\", // 图片缩略图\n                        \"original_path\": \"http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270\" // 图片原图\n                    }\n                ]\n            }\n        ]\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/CorevalueController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordIntro"
  },
  {
    "type": "get",
    "url": "/keyword/news",
    "title": "关键词：新闻",
    "description": "<p>关键词：新闻 (彦平)</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码(非必传，不传默认为1)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>每页显示数量(非必传,默认：5)</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n    \"statusCode\": 200,\n    \"statusMessage\": \"获取数据成功\",\n    \"responseData\": {\n        \"keywords\": {\n            \"code\": \"gp51YWgemGows7Dw\",             // 关键词code\n            \"name_cn\": \"Perceptive Pixel\",          // 关键词中文名称\n            \"name_en\": \"Perceptive Pixel\",          // 关键词英文名称\n            \"category_code\": \"KC002\",               // 关键词类型code\n            \"oneword\": \"多点触控面板设计研发商\",    // 关键词一句话\n            \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495098128.jpg!initial\",     //关键词logo\n            \"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1496887552.jpg!initial\",     //关键词头图\n            \"desc\": \"Perceptive Pixel于2006年成立于纽约，是一家多点触控面板设计研发商，创始人是Jefferson Han。2012年，该公司被微软收购。\",     //关键词描述\n            \"birth_info\": \"创立于：2006年\"           //关键词生日、创立信息\n        },\n        \"news\": {\n            \"current_page\": 1,\n            \"last_page\": 1,\n            \"info\": [\n                {\n                    \"keyword_code\": \"QujrFl7lIYOxssWM\",\n                    \"keyword_name\": \"FOVE\",\n                    \"industry_code\": \"IND0022\",\n                    \"industry_fullname\": \"虚拟现实 / 头戴式显示器\",\n                    \"draft_id\": 4,\n                    \"title\": \"能追踪眼球的VR头盔面世\",\n                    \"subtitle\": \"局部变焦或将引变革\",\n                    \"imgs\": [                                       //新闻图片\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654357.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654364.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1506477562.jpg\"\n                    ],\n                    \"intro\": \"根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学\"      //新闻简介                             //新闻简介\n                }\n            ]\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/KeywordController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordNews"
  },
  {
    "type": "get",
    "url": "/keyword/people",
    "title": "关键词：人物",
    "description": "<p>关键词：人物------------------------------------王磊</p>",
    "group": "Keyword",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keyword_code",
            "description": "<p>关键词code（W1UN4YbN4pKnuvR6，2ig9m3grokbYsYdD，ucU2fdKmdqW00O2P，nL9TFlR4CFDcUY6U，jqcFxIzHBVU4VStV，1dXpLvKervsLI9hY，7cGL4HlQMntNy1cC，ZQ466shCj9Nj3ajO）</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n   {\n       \"statusCode\": \"501\",\n       \"statusMessage\": \"非法数据请求\",\n       \"responseData\": {\n           \"keyword\": {\n               \"code\": \"W1UN4YbN4pKnuvR6\",   // 关键词code\n               \"name_cn\": \"太空探索技术公司\",  // 关键词中文名\n               \"name_en\": \"SpaceX\",  // 关键词英文名\n               \"category_code\": \"KC002\",  // 关键词类型code\n               \"oneword\": \"太空运输服务商\",  // 关键词一句话\n               \"logo_path\": \"http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial\",   // 关键词缩略图\n               \"head_path\": \"http://uploads2.b0.upaiyun.com//keywords2/key1495701352.jpg!news.logo.500.270\",  // 关键词头图\n               \"desc\": \"SpaceX于2002年成立于美国加州，是一家太空运输服务公司、火箭回收技术研发商，创始人为埃隆·马斯克。\", // 关键词简介\n               \"birth_info\": \"成立于：2002年06月\" // 地点时间 \n           },\n           \"people\": [\n               {\n                   \"code\": \"NvXUKMnqdD2FVol6\",  // 人物关键词code\n                   \"name_cn\": \"伊隆·马斯克\",  // 人物关键词中文名\n                   \"name_en\": \"Elon Musk\",  // 人物关键词英文名\n                   \"head_path\": \"\",  // 人物关键词头图\n                   \"role\": \"CEO\"  // 人物关键词职务\n               }\n           ]\n       }\n   }",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/PeopleController.php",
    "groupTitle": "Keyword",
    "name": "GetKeywordPeople"
  },
  {
    "type": "get",
    "url": "/channel",
    "title": "频道首页",
    "description": "<p>频道首页 --- 彦平</p>",
    "group": "Main",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "industry_code",
            "description": "<p>行业类型code(必传  eg: IND0021)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码(非必传，不传默认为1)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>每页显示数量(非必传，不传默认为5)</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n   {\n       \"statusCode\": 200,\n       \"statusMessage\": \"获取数据成功\",\n       \"responseData\": {\n           \"current_page\": 1,\n           \"last_page\": 1,\n           \"data\": [\n               {\n                   \"keyword_code\": \"QujrFl7lIYOxssWM\",         //关键词code\n                   \"keyword_name\": \"FOVE\",                     //关键词名称\n                   \"industry_code\": \"IND0022\",                 //行业类型code\n                   \"industry_fullname\": \"虚拟现实 / 头戴式显示器\",   //一二级行业类型\n                   \"draft_id\": 4,                                //新闻id\n                   \"title\": \"能追踪眼球的VR头盔面世\",            //新闻标题\n                   \"subtitle\": \"局部变焦或将引变革\",            //新闻副标题\n                   \"imgs\": [                                   //新闻图片\n                       \"http://uploads2.b0.upaiyun.com///news/news1496654247.jpg\",\n                       \"http://uploads2.b0.upaiyun.com///news/news1496654247.jpg\"\n                   ],\n                   \"intro\": null                               //新闻简介\n               }\n           ]\n       }\n   }",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/HomeController.php",
    "groupTitle": "Main",
    "name": "GetChannel"
  },
  {
    "type": "get",
    "url": "/home",
    "title": "首页",
    "description": "<p>首页 --- 彦平</p>",
    "group": "Main",
    "permission": [
      {
        "name": "需要"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码(非必传，不传默认为1)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>每页显示数量(非必传,默认：5)</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n    \"statusCode\": 200,\n    \"statusMessage\": \"获取数据成功\",\n    \"responseData\": {\n        \"current_page\": 1,\n        \"last_page\": 1,\n        \"data\": [\n            {\n                \"keyword_code\": \"UBDp4pOVRKf0q2wW\",             //关键词code\n                \"keyword_name\": \"NextVR\",                       //关键词名称\n                \"industry_code\": \"IND0020\",                     //行业类型code\n                \"industry_fullname\": \"虚拟现实 / 虚拟现实直播\", //一二级行业类型\n                \"draft_id\": 1,                                  //新闻id\n                \"title\": \"NextVR领衔\",                          //新闻标题\n                \"subtitle\": \"虚拟现实VR直播来袭\",               //新闻副标题\n                \"imgs\": [                                       //新闻图片\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654357.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654364.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1506477562.jpg\"\n                ],\n                \"intro\": \"根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学、李嘉诚基金会、市环保局负责配合做好此项工作，由汕头市环境保护研究所抽调精干专业技术力量协调配合开展科研项目调查采样，协助Nanospun科技有限公司开展科研项目初步研究、资料收集和地点选择等一系列工作；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂\"   //新闻简介\n            },\n            {\n                \"keyword_code\": \"svC6eXb8uKQoWULI\",\n                \"keyword_name\": \"UrtheCast\",\n                \"industry_code\": \"IND0031\",\n                \"industry_fullname\": \"卫星技术 / 应用卫星\",\n                \"draft_id\": 2,\n                \"title\": \"这家公司把摄像头安到了国际空间站\",\n                \"subtitle\": \"想要从太空中直播地球\",\n                \"imgs\": [                                       //新闻图片\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654357.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654364.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1506477562.jpg\"\n                ],\n                \"intro\": null                                   //新闻简介\n            },\n            {\n                \"keyword_code\": \"QujrFl7lIYOxssWM\",\n                \"keyword_name\": \"FOVE\",\n                \"industry_code\": \"IND0022\",\n                \"industry_fullname\": \"虚拟现实 / 头戴式显示器\",\n                \"draft_id\": 4,\n                \"title\": \"能追踪眼球的VR头盔面世\",\n                \"subtitle\": \"局部变焦或将引变革\",\n                \"imgs\": [                                       //新闻图片\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654357.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1496654364.jpg\",\n                    \"http://uploads2.b0.upaiyun.com///news/news1506477562.jpg\"\n                ],\n                \"intro\": null                                   //新闻简介\n            }\n        ]\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/HomeController.php",
    "groupTitle": "Main",
    "name": "GetHome"
  },
  {
    "type": "get",
    "url": "/navigation",
    "title": "频道导航",
    "description": "<p>频道导航------------------------------------王磊</p>",
    "group": "Main",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "null",
            "description": "<p>无</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n   {\n       \"statusCode\": 4000,\n       \"statusMessage\": \"获取数据成功\",\n       \"responseData\": [\n           {\n               \"code\": \"IND0002\", // 一级行业code\n               \"name\": \"基础物理\", // 一级行业中文名\n               \"name_en\": \"Fundamental Physics\", // 一级行业英文名\n               \"second_industry\": [ \n                   {\n                       \"code\": \"IND0097\", // 二级行业code\n                       \"name\": \"热力学\", // 二级行业中文名\n                       \"name_en\": \"\" // 二级行业英文名\n                   },\n                   {\n                       \"code\": \"IND0098\",\n                       \"name\": \"量子力学\",\n                       \"name_en\": \"\"\n                   },\n                   {\n                       \"code\": \"IND0099\",\n                       \"name\": \"电磁力学\",\n                       \"name_en\": \"\"\n                   }\n               ]\n           }\n       ]\n   }",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/IndustryController.php",
    "groupTitle": "Main",
    "name": "GetNavigation"
  },
  {
    "type": "get",
    "url": "/program",
    "title": "节目",
    "description": "<p>节目 ———— 朱朔男</p>",
    "group": "Main",
    "permission": [
      {
        "name": "需要验证"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "program_type",
            "description": "<p>节目类型:1-新城商业(默认),2-像素科技</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  HTTP/1.1 201 Created\n {\n\t  \"statusCode\": 200,\n\t  \"statusMessage\": \"获取数据成功\",\n\t  \"responseData\": {\n\t    \"total\": 118,\n\t    \"per_page\": \"1\",\n\t    \"current_page\": 1,\n\t    \"last_page\": 118,\n\t    \"next_page_url\": \"http://www.xcapp.com/v1/program?page=2\",\n\t    \"prev_page_url\": null,\n\t    \"from\": 1,\n\t    \"to\": 1,\n\t    \"data\": [\n\t      {\n\t        \"title\": \"打针竟然改在肠道中进行\",\t// 视频标题\n\t        \"issue\": \"第118期\",\t\t\t\t\t// 期数\n\t        \"thumb_path\": \"http://uploads2.b0.upaiyun.com///vb/vb1506045098.jpg\",\t// 图片路径\n\t        \"type_code_website\": null,\t\t\t// 来自网站\n\t        \"link\": \"XMzAzODM0NzczNg\"\t\t\t// 视频连接\n\t      }\n\t    ]\n\t  }\n\t}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/V2/ProgramController.php",
    "groupTitle": "Main",
    "name": "GetProgram"
  }
] });
