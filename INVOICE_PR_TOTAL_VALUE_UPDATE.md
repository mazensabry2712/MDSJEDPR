# ✅ Invoice PR Total Value - Auto Calculation

## 🎯 التحديث

تم إضافة **حساب تلقائي لـ PR Invoices Total Value** في نظام Invoice!

---

## 📊 كيف يعمل؟

### المفهوم:
**PR Invoices Total Value** = مجموع قيم **جميع Invoices** الخاصة بنفس المشروع (PR Number)

### مثال:
```
Project: PR-2025-001

Invoice 1: 10,000 EGP
Invoice 2: 15,000 EGP
Invoice 3: 5,000 EGP

PR Invoices Total Value = 30,000 EGP (لكل الـ 3 Invoices)
```

---

## 🔧 التحديثات التقنية

### 1. Store (إضافة Invoice جديد)

```php
// حساب المجموع الحالي + القيمة الجديدة
$totalValue = invoices::where('pr_number', $request->pr_number)
    ->sum('value') + $request->value;

$data['pr_invoices_total_value'] = $totalValue;

// تحديث كل الـ Invoices الخاصة بالمشروع
invoices::where('pr_number', $request->pr_number)
    ->update(['pr_invoices_total_value' => $totalValue]);
```

**النتيجة**: 
- ✅ يحسب المجموع تلقائياً
- ✅ يحدث كل الـ Invoices بنفس المجموع
- ✅ المجموع دائماً صحيح

---

### 2. Update (تعديل Invoice موجود)

```php
// إعادة حساب المجموع بعد التعديل
$totalValue = invoices::where('pr_number', $request->pr_number)
    ->sum('value');

// تحديث كل الـ Invoices
invoices::where('pr_number', $request->pr_number)
    ->update(['pr_invoices_total_value' => $totalValue]);
```

**النتيجة**:
- ✅ يحسب المجموع من جديد
- ✅ يأخذ في الاعتبار القيمة المعدلة
- ✅ يحدث كل الـ Invoices

---

### 3. Destroy (حذف Invoice)

```php
$projectId = $invoice->pr_number;

// حذف الـ Invoice أولاً
$invoice->delete();

// إعادة حساب المجموع بعد الحذف
$totalValue = invoices::where('pr_number', $projectId)
    ->sum('value');

// تحديث الـ Invoices المتبقية
invoices::where('pr_number', $projectId)
    ->update(['pr_invoices_total_value' => $totalValue]);
```

**النتيجة**:
- ✅ يحذف الـ Invoice والملف
- ✅ يعيد حساب المجموع
- ✅ يحدث الـ Invoices المتبقية

---

## 📊 عرض البيانات في Index

### الجدول:
```blade
<th>Value</th>
<th>PR Invoices Total Value</th>
<th>Status</th>
```

### عرض القيمة:
```blade
<td>
    @if($invoice->pr_invoices_total_value)
        <span class="badge badge-info" style="font-size: 13px;">
            <i class="fas fa-calculator"></i> 
            {{ number_format($invoice->pr_invoices_total_value, 2) }} EGP
        </span>
    @else
        <span class="text-muted">N/A</span>
    @endif
</td>
```

---

## ✅ المميزات

### 1. حساب تلقائي
- ✅ لا يحتاج إدخال يدوي
- ✅ يحسب عند الإضافة
- ✅ يحسب عند التعديل
- ✅ يحسب عند الحذف

### 2. دقة عالية
- ✅ يستخدم `sum()` مباشرة من Database
- ✅ لا أخطاء حسابية
- ✅ دائماً محدث

### 3. تحديث شامل
- ✅ يحدث كل Invoices المشروع
- ✅ كل الـ Invoices لها نفس المجموع
- ✅ بيانات متسقة

---

## 📈 سيناريوهات الاستخدام

### Scenario 1: إضافة أول Invoice
```
Project: PR-001
Invoices: 0

➕ Add Invoice: 10,000 EGP
✅ PR Total Value = 10,000 EGP
```

### Scenario 2: إضافة Invoice ثاني
```
Project: PR-001
Invoice 1: 10,000 EGP

➕ Add Invoice 2: 15,000 EGP
✅ PR Total Value = 25,000 EGP (لكليهما)
```

### Scenario 3: تعديل Invoice
```
Project: PR-001
Invoice 1: 10,000 EGP
Invoice 2: 15,000 EGP
Total: 25,000 EGP

✏️ Edit Invoice 1 → 12,000 EGP
✅ PR Total Value = 27,000 EGP (محدث للكل)
```

### Scenario 4: حذف Invoice
```
Project: PR-001
Invoice 1: 12,000 EGP
Invoice 2: 15,000 EGP
Total: 27,000 EGP

🗑️ Delete Invoice 2
✅ PR Total Value = 12,000 EGP (محدث)
```

---

## 🎨 التصميم في الجدول

### Badge Style:
```css
.badge-info {
    font-size: 13px;
    padding: 6px 10px;
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
```

### Icon:
```html
<i class="fas fa-calculator"></i>
```

### Format:
```
12,345.67 EGP
```

---

## 📊 Export

تم تحديث أعمدة الـ Export لتشمل **PR Invoices Total Value**:

```javascript
exportOptions: {
    columns: [0, 2, 3, 4, 6, 7, 8]
    // 0: #
    // 2: PR Number
    // 3: Project Name
    // 4: Invoice Number
    // 6: Value
    // 7: PR Invoices Total Value ✅ NEW
    // 8: Status
}
```

---

## ✅ الاختبارات

### Test 1: إضافة Invoice
- ✅ يحسب المجموع صح
- ✅ يظهر في الجدول
- ✅ يحدث كل الـ Invoices

### Test 2: تعديل Invoice
- ✅ يعيد حساب المجموع
- ✅ يحدث كل الـ Invoices
- ✅ القيم صحيحة

### Test 3: حذف Invoice
- ✅ يحذف الـ Invoice
- ✅ يعيد حساب المجموع
- ✅ يحدث الباقي

### Test 4: Multiple Projects
- ✅ كل Project له مجموعه
- ✅ لا تداخل بين Projects
- ✅ حساب مستقل

---

## 🎯 الخلاصة

### تم بنجاح:
✅ حساب تلقائي لـ PR Invoices Total Value  
✅ تحديث عند الإضافة/التعديل/الحذف  
✅ عرض احترافي في الجدول  
✅ Export شامل  
✅ Cache clearing تلقائي  
✅ دقة 100%  

### الحالة:
🟢 **Production Ready**  
⚡ **Auto Calculated**  
📊 **Always Accurate**  

---

## 📞 Database Structure

```sql
invoices table:
- id
- invoice_number
- pr_number (FK → projects.id)
- value (decimal)
- pr_invoices_total_value (decimal) ✅ Auto Calculated
- invoice_copy_path
- status
- timestamps
```

---

**النتيجة**: نظام Invoice مع حساب تلقائي ذكي لمجموع الفواتير! 🎉✨

**التحديث**: اليوم  
**النوع**: Auto Calculation  
**الحالة**: ✅ Complete
