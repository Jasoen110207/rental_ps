# Product Requirements Document: TambahBang - Rental PlayStation Management Website

## Problem Statement
Mengelola bisnis rental PlayStation melibatkan *tracking usage*, menangani *billing*, dan memastikan *customer experience* yang mulus. Solusi yang ada saat ini seringkali terfragmentasi, memerlukan intervensi manual, dan kurangnya *real-time monitoring*, yang dapat menyebabkan inefisiensi dan ketidakpuasan pelanggan. Terdapat kebutuhan akan sistem terintegrasi yang memungkinkan *kasir* mengelola *billing*, memantau *usage*, dan menangani *customer requests* dengan efisien.

## Goals & Success Metrics
### Goals
1. **Efficiency:** Menyederhanakan proses penyewaan dan *billing*.
2. **User Experience:** Menyediakan *interface* yang *seamless* dan intuitif bagi *kasir* maupun *user*.
3. **Monitoring:** Memungkinkan *real-time monitoring* untuk *usage* unit rental PlayStation.
4. **Flexibility:** Menawarkan opsi *billing prepaid* dan *postpaid*.

### Success Metrics
1. **Reduction in Billing Errors:** Menurun sebesar 80% dalam 6 bulan pertama.
2. **Customer Satisfaction:** Mencapai tingkat kepuasan 90% yang diukur melalui *post-rental surveys*.
3. **Usage Monitoring:** Memastikan akurasi 100% dalam *real-time usage tracking*.
4. **System Uptime:** Mempertahankan *uptime* 99,9%.

## User Stories
1. **As a kasir,** saya ingin melihat *real-time usage* dari unit rental PlayStation, sehingga saya dapat mengelola *inventory* dan *availability*.
2. **As a kasir,** saya ingin menambah atau melakukan *update billing* untuk unit rental PlayStation, sehingga saya dapat memastikan *charges* yang akurat.
3. **As a user,** saya ingin melakukan *request* tambahan waktu *billing* melalui *QR code*, sehingga saya dapat melakukan *extend rental session* saya dengan mudah.
4. **As a user,** saya ingin melakukan *order* makanan dan minuman melalui *QR code*, sehingga saya dapat meminimalkan gangguan selama *rental session* saya.
5. **As a kasir,** saya ingin mengelola opsi *billing prepaid* dan *postpaid*, sehingga saya dapat mengakomodasi berbagai *customer preferences*.
6. **As a kasir,** saya ingin melakukan *switch shifts* dengan lancar, sehingga saya dapat memastikan *continuous service*.
7. **As a kasir,** saya ingin menerima *time warnings* untuk *billing deadlines* yang akan datang, sehingga saya dapat menginformasikan *customer* sebelumnya.

## Functional Requirements
1. **User Roles:**
   - **Kasir:** Mengelola *billing*, memantau *usage*, melakukan *switch shifts*, dan menangani *customer requests*.
   - **User:** Melakukan *request* tambahan waktu *billing* dan melakukan *order* makanan/minuman melalui *QR codes*.

2. **Billing System:**
   - Mendukung opsi *billing prepaid* dan *postpaid*.
   - Memungkinkan *kasir* untuk menambah atau melakukan *update billing* untuk *rental sessions*.
   - Menyediakan *real-time updates* pada status *billing*.

3. **Usage Monitoring:**
   - Menampilkan *real-time usage* dari unit rental PlayStation pada *dashboard kasir*.
   - Memungkinkan *kasir* untuk mengaktifkan atau menonaktifkan *rental units* secara manual.

4. **Customer Requests:**
   - Memungkinkan *user* untuk melakukan *request* tambahan waktu *billing* dengan melakukan *scanning QR code*.
   - Memungkinkan *user* untuk melakukan *order* makanan dan minuman dengan melakukan *scanning QR code*.
   - Memberikan *notifications* kepada *kasir* mengenai *user requests* melalui *dashboard*.

5. **Shift Management:**
   - Memungkinkan *kasir* untuk *log in* dan *log out* dari *shifts*.
   - Menyediakan transisi yang *seamless* antar *shifts* tanpa kehilangan data atau *state*.

6. **Time Warnings:**
   - Mengirimkan *notifications* kepada *kasir* dan *user* tentang *billing deadlines* yang akan datang.
   - Memberikan *warnings* untuk *time extensions* dan *billing updates*.

## Non-Functional Requirements
1. **Performance:**
   - Memastikan *website* dapat menangani *high traffic* selama *peak hours*.
   - Mempertahankan *response time* kurang dari 1 detik untuk *user interactions*.

2. **Security:**
   - Mengimplementasikan mekanisme *secure login* dan *authentication*.
   - Melindungi *user data* dan *billing data* dengan enkripsi.

3. **Scalability:**
   - Melakukan *design* pada sistem untuk menangani peningkatan jumlah *rental units* dan *users*.
   - Memastikan *database* dapat melakukan *scale* untuk mendukung peningkatan *data volume*.

4. **User Interface:**
   - Mengikuti *design theme neo brutalism* dengan warna dominan *blue* dan *orange*.
   - Memastikan *interface* bersifat intuitif dan *user-friendly* bagi *kasir* maupun *user*.
