# 2024六一儿童节比赛 题解
首先向大家说一声：

> 六一儿童节快乐！

## 目录
1. [A](#a)
2. [B](#b)
3. [C](#c)
4. [D](#d)
## A
**so esey.**

看题目**sort**，代码：
```cpp
#include <bits/stdc++.h>
using namespace std;
string a;
int main(){
	cin >> a;
	sort(a.begin(),a.end());
	cout << a;
}
```
居然还因为样例有bug，有人写：
```cpp
#include<bits/stdc++.h>
using namespace std;
string s;
int main(){
    cin>>s;
    if(s.size()<=6)sort(s.begin(), s.end());
    cout<<s;
}
```
还AC了。
## B
输出`What's the time, Mr Wolf?`即可。
## C
~~**好难啊。**~~

大哥/大姐，把那个`68747470733a2f2f67697465652e636f6d2f73756e6e792d746f776e2f3631657274`十六进制转字符串即可，在输出字符串里链接的内容。
## D
~~《小心TLE》~~

直接：
```python
while True:
    pass
```
