package so.doo.app.plugins;

/**
 * 服务跨进程数据发送
 *
 * 基于AIDL
 *
 */
interface Listening {
    // 添加监听
    void addApp(String l, String t);
}
