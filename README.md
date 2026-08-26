# KODAMA

**日本語学校 / 塾向けの学生カルテ・出欠管理システム**
A student-records and attendance-management system for Japanese language schools, built in PHP.

> A solo-built, production-used school management system (2018–2020). Its distinctive feature is
> **inferring the school calendar from raw check-in data** instead of maintaining one by hand —
> a class with zero check-ins in a time slot is recorded as a school holiday, over 50% as a held
> class, and anything in between is flagged for a human to confirm. Also includes a colour-coded
> monthly attendance ledger and one-click generation of the official PDF certificates
> (immigration applications, enrolment/graduation certificates) that these schools file constantly.

---

## 这是什么

日本的语言学校和私塾有两件事天天要做,而且都很烦:

1. **考勤** —— 不只是记出勤,还要按「コマ数」(课时)算出席率。入国管理局审查留学生在留资格时会查这个数字,算错是要出事的。
2. **出证明** —— 在学証明書、卒業証明書、在留資格認定証明書交付申請書…… 每份都是有固定格式的官方表格,手填耗时且容易错。

KODAMA 把这两件事做成了一套系统:学生档案是数据源,考勤自动统计,证明书从档案直接生成 PDF。

这是 2018 年开始、一个人从零手写的项目 —— 没有框架,没有 Composer,PHP + jQuery + 原生 SQL。代码部分从数据库设计到前端全部由我完成,产品与测试由同事负责。它在真实的语言学校里跑过。

---

## 亮点:用签到数据反推校历

大多数考勤系统要求你先维护一份校历,告诉它哪天上课、哪天放假。这在语言学校不现实 —— 临时调课、国定假日、学校自定的休校日太多,维护校历本身就成了负担,而且一旦忘记更新,出席率就全错。

KODAMA 反过来做:**不问校历,只看签到数据**。

对每个班级的每个课段,统计签到人数与在籍人数之比:

| 签到率 | 判定 | 说明 |
|---|---|---|
| 0% | `休` 休校日 | 一个人都没来 → 这天本来就没课 |
| > 50% | `出` 正常上课 | 计入课时,参与出席率计算 |
| 0% ~ 50% | `不` 不明 | 不自动判定,标记出来等负责人确认 |

实现在 [`attend/situation_class.php:109`](attend/situation_class.php#L109)。

中间档不猜、而是交回给人,是这个设计里我自己最满意的地方 —— 出席率是要拿去给入管看的数字,系统在没把握的时候应该说「我不确定」,而不是给一个看起来很确定的错数。

签到时间窗口允许提前 / 延后(`classtime` 表的 `aheadperiod` / `delayperiod`),迟到早退阈值(`allowlate` / `allowearly`)也可配置。

---

## 功能

### 出欠管理

- **签到界面**:个人签到 / 集体签到两种模式,支持远程设备通过 HTTP 接口打卡([`attend/AddAttendRecordGet.php`](attend/AddAttendRecordGet.php))
- **月度考勤簿**:31 天 × 8 种出勤状态的颜色矩阵,一屏看完一个学生或一个班一个月的全貌

  状态:`出` 出席 · `欠` 欠席 · `遅` 遅刻早退 · `公` 公欠 · `休` 休学 · `帰` 一時帰国 · `-` 休校日 · `不` 不明
- **双口径统计**:按 **コマ数**(课时)和按 **日数** 分别计算出席率 —— 入管看课时,学校内部管理看日数
- **人工修正**:自动判定的记录可手工覆盖,`manualmodified` 标记保留修改痕迹
- **统计重建**:[`attend/situation_rebuildall.php`](attend/situation_rebuildall.php) 可从原始签到记录全量重算,规则改了不用怕

### 学生档案

学籍信息、在留资格信息(在留カード番号 / パスポート / 在留期限)、成绩、面谈记录、赏罚、进路(升学就职)、学费缴纳、作品集 —— 分表存储,`student` 存基本信息,`student2` 存在留相关,其余各成一表。

### 证明书 PDF 生成

基于 TCPDF,套用真实表格版式直接填充学生数据:

| | |
|---|---|
| 在学証明書 | 卒業証明書 |
| 在籍証明書 | 卒業見込証明書 |
| 修了証明書 | 修了見込証明書 |
| 修了証書 | 卒業証書 |
| 退学証明書 | 推薦書 |
| 学業成績及び出席状況証明書 | 承认书(再入国) |

以及最复杂的那份:**在留資格認定証明書交付申請書**(入国管理局在留资格认定申请书),多页、字段上百。

### 权限与账号

三级角色(管理者 / 教師 / 仲介),由 `userrights` 表控制;邮件找回密码(PHPMailer + `token` 表,token 两天过期)。

### 界面

日语 / 中文双语界面,多套主题配色可切换(cookie 记住选择)。

---

## 快速开始

**环境**:PHP 7.4+(含 PDO / GD 扩展) · MySQL 5.7+ · Apache 或 Nginx

```bash
git clone https://github.com/hera2019/kodama.git
cd kodama
```

**1. 建库并导入演示数据**

```bash
mysql -u root -p -e "CREATE DATABASE kodama_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;"
```

```bash
mysql -u root -p kodama_demo < db/kodama_demo.sql
```

**2. 配置数据库连接**

```bash
cp config/config.example.php config/config.php
```

编辑 `config/config.php` 填入数据库信息。该文件已在 `.gitignore` 中,不会被提交。

**3. 指向 Web 根目录后访问**

演示账号:

| 用户名 | 密码 |
|---|---|
| `admin` | `kodama-demo` |

演示数据包含 2 个班级、约 30 名学生、2019-08 至 2020-02 近千条签到记录 —— 足够看到考勤矩阵和自动休校日判定的实际效果。所有学生姓名、照片、联系方式均为虚构或占位内容。

---

## 目录结构

```
kodama/
├── index.php              程序入口
├── attend/                签到与出欠统计
│   ├── attend_class.php       签到记录操作类
│   ├── CheckInUI*.php         签到界面(个人 / 集体)
│   ├── situation_class.php    ★ 班级出勤统计 + 休校日自动判定
│   ├── situation_month.php    月度统计生成
│   └── situation_rebuildall.php  全量重建统计
├── page/                  各功能页面(学生管理 / 班级 / 成绩 / 证明书…)
│   ├── PDFWrite*.php          各类证明书 PDF 生成
│   └── IB-Admission*.php      在留資格認定証明書交付申請書
├── dataproc/              数据处理层(*_class.php 数据类 / *_proc.php Ajax 接口)
├── frame/                 页面框架(头部 / 侧栏 / 底部)
├── include/               数据库连接与全局函数
├── user/                  登录 / 注册 / 密码重置
├── mail/                  邮件发送
├── template/pdf/          证明书 PDF 版式模板
├── config/                数据库配置(config.php 需自行创建)
├── db/kodama_demo.sql     演示数据库
├── data/photo/            学生照片(仓库内仅占位图)
├── style/                 前端资源
└── plugin/                第三方库(TCPDF / PHPMailer / class.upload)
```

---

## 数据模型

22 张表。核心几张:

| 表 | 作用 |
|---|---|
| `student` / `student2` | 学生基本信息 / 在留资格信息 |
| `attendance` | 原始签到记录,每天一行,最多 4 个课段(`time11`~`time42`) |
| `situationclass` | 按班级 · 课段聚合的出勤统计,含休校日判定结果 |
| `situationmonth` | 按学生 · 月聚合的出勤统计,JSON 存每日状态 |
| `classtime` | 课段时间表与迟到早退阈值 |
| `attendproperty` | 出勤状态字典(出 / 欠 / 遅 / 公 / 休 / 帰 / - / 不) |
| `idconfig` | 通用字典表(国籍 / 在籍状态 / 课程 / 在留资格 / 进路…) |
| `studentdata` | 申请表单数据,JSON 存储 |
| `usermanage` / `userrights` | 用户与权限 |
| `operatelog` | 操作日志 |

---

## 技术栈

| | |
|---|---|
| 后端 | PHP 7.4,PDO 预处理语句,无框架 |
| 数据库 | MySQL(MyISAM),utf8mb4 |
| 前端 | Bootstrap 3 + AdminBSB 主题,jQuery,DataTables,bootstrap-treeview,bootstrap-datetimepicker |
| PDF | TCPDF |
| 邮件 | PHPMailer |
| 上传 | class.upload.php |

自有代码约 14,000 行(PHP + JS),不含第三方库。

---

## 已知局限

这是 2018–2020 年的代码,以今天的标准看有几处明显不足,列在这里而不是藏起来:

- **密码用 MySQL `SHA()` 存储**,即无加盐 SHA-1。当年常见,今天应该用 `password_hash()` / bcrypt。
- **无 CSRF 防护**,表单提交没有 token 校验。
- **MyISAM 引擎**,没有外键约束和事务,表间一致性靠应用层保证。
- **数据处理层与展示层耦合**,`dataproc/*_class.php` 里 SQL、业务逻辑和 HTML 拼装混在一起。
- **无自动化测试**,`test/` 目录是当年调试前端组件用的草稿,不是测试套件。
- **依赖靠手工放入 `plugin/`**,没有 Composer。

如果今天重写,我会用 Laravel 或 Slim + Composer,数据库换 InnoDB 并加外键,认证交给框架,把出勤判定逻辑抽成独立的、可测试的领域服务 —— 那段逻辑是整个系统里最值得写单元测试的部分,当年却完全靠手工验证。

---

## 关于这份代码

- **2018 年**开始开发,当时还不会用 Git,靠手工备份文件夹
- **2019 年 11 月**起纳入 Git 管理,仓库中的提交历史从这里开始
- **2019–2020 年**为主力开发期,2021、2023 年有零星维护提交
- 代码由一人完成,产品与测试由同事负责
- 曾在真实的日本語学校环境中运行

本仓库为公开存档,已移除全部生产环境凭据、真实学校信息与个人数据;演示数据库中的学生姓名、照片、联系方式均为虚构或占位内容。项目不再维护。
