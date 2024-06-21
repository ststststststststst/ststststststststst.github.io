# [Luogu - T460386 缺失的数字](https://www.luogu.com.cn/problem/T460386)
题解提供者：[sunny_town](https://www.luogu.com.cn/user/1240580)

**输入的数组是无序的！**

~~出题人疯了吗？时间只有**20ms**！~~

```cpp
#include <bits/stdc++.h>  // 使用万能头文件，包含了所有标准库头文件
using namespace std;

// 找出缺失的两个整数的函数
vector<int> work(int n, vector<int>& x) {
    unordered_set<int> s(x.begin(), x.end());  // 将输入的整数存入无序集合中，便于快速查找
    vector<int> m;  // 用于存放找到的缺失整数
    // 遍历从 1 到 n 的每个整数
    for (int i = 1; i <= n; ++i) {
        // 如果 i 不在集合 s 中（即 x 中没有 i），则将 i 加入到 m 中
        if (s.find(i) == s.end()) {
            m.push_back(i);
            // 一旦找到两个缺失的整数，就跳出循环，不再继续查找
            if (m.size() == 2) break;
        }
    }
    return m;  // 返回找到的两个缺失整数
}

int main() {
    ios::sync_with_stdio(false);  // 优化输入输出流的速度，避免在多个流之间频繁切换，提高效率
    cin.tie(nullptr);  // 解除 cin 和 cout 的绑定，进一步提高输入效率
    int n;
    cin >> n;  // 读取输入的整数 n，表示整数的范围
    vector<int> y(n - 2);  // 定义一个 vector y，大小为 n-2，用来存放输入的 n-2 个整数
    // 依次读取 n-2 个整数并存入 y 中
    for (int i = 0; i < n - 2; ++i) {
        cin >> y[i];
    }
    // 调用 work 函数，传入 n 和 y，得到存放缺失整数的 ans
    vector<int> ans = work(n, y);
    // 输出找到的两个缺失整数
    cout << ans[0] << " " << ans[1];
    return 0;
}
```

`work` 函数的目的是找出范围 `[1, n]` 中缺失的两个整数。它接受两个参数：`n` 表示整数的范围，`x` 是一个向量，包含了缺失的整数（即少了两个整数的部分）。

1. 首先，将向量 `x` 中的元素放入一个 `unordered_set` 中，以实现快速的查找操作。
2. 然后从 `1` 开始遍历到 `n`，对于每个整数 `i`，如果它不在集合 `s` 中（即 `x` 中没有 `i`），则将其加入向量 `m` 中。
3. 当向量 `m` 的大小达到 `2` 时，就跳出循环，因为已经找到了两个缺失的整数。
4. 最后返回向量 `m`，其中存放着找到的两个缺失整数。

# GoodBye~

支持平台：[sunny_town的博客](https://ststststststststst.github.io/)
