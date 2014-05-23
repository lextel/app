/*
 * This file is auto-generated.  DO NOT MODIFY.
 * Original file: /Users/weelion/Development/lltao/app/lltao/platforms/android/src/so/doo/app/plugins/Listening.aidl
 */
package so.doo.app.plugins;
/**
 * 服务跨进程数据发送
 *
 * 基于AIDL
 *
 */
public interface Listening extends android.os.IInterface
{
/** Local-side IPC implementation stub class. */
public static abstract class Stub extends android.os.Binder implements so.doo.app.plugins.Listening
{
private static final java.lang.String DESCRIPTOR = "so.doo.app.plugins.Listening";
/** Construct the stub at attach it to the interface. */
public Stub()
{
this.attachInterface(this, DESCRIPTOR);
}
/**
 * Cast an IBinder object into an so.doo.app.plugins.Listening interface,
 * generating a proxy if needed.
 */
public static so.doo.app.plugins.Listening asInterface(android.os.IBinder obj)
{
if ((obj==null)) {
return null;
}
android.os.IInterface iin = obj.queryLocalInterface(DESCRIPTOR);
if (((iin!=null)&&(iin instanceof so.doo.app.plugins.Listening))) {
return ((so.doo.app.plugins.Listening)iin);
}
return new so.doo.app.plugins.Listening.Stub.Proxy(obj);
}
@Override public android.os.IBinder asBinder()
{
return this;
}
@Override public boolean onTransact(int code, android.os.Parcel data, android.os.Parcel reply, int flags) throws android.os.RemoteException
{
switch (code)
{
case INTERFACE_TRANSACTION:
{
reply.writeString(DESCRIPTOR);
return true;
}
case TRANSACTION_addApp:
{
data.enforceInterface(DESCRIPTOR);
java.lang.String _arg0;
_arg0 = data.readString();
java.lang.String _arg1;
_arg1 = data.readString();
this.addApp(_arg0, _arg1);
reply.writeNoException();
return true;
}
}
return super.onTransact(code, data, reply, flags);
}
private static class Proxy implements so.doo.app.plugins.Listening
{
private android.os.IBinder mRemote;
Proxy(android.os.IBinder remote)
{
mRemote = remote;
}
@Override public android.os.IBinder asBinder()
{
return mRemote;
}
public java.lang.String getInterfaceDescriptor()
{
return DESCRIPTOR;
}
// 添加监听

@Override public void addApp(java.lang.String l, java.lang.String t) throws android.os.RemoteException
{
android.os.Parcel _data = android.os.Parcel.obtain();
android.os.Parcel _reply = android.os.Parcel.obtain();
try {
_data.writeInterfaceToken(DESCRIPTOR);
_data.writeString(l);
_data.writeString(t);
mRemote.transact(Stub.TRANSACTION_addApp, _data, _reply, 0);
_reply.readException();
}
finally {
_reply.recycle();
_data.recycle();
}
}
}
static final int TRANSACTION_addApp = (android.os.IBinder.FIRST_CALL_TRANSACTION + 0);
}
// 添加监听

public void addApp(java.lang.String l, java.lang.String t) throws android.os.RemoteException;
}
