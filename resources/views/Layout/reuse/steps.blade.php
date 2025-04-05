<style>
    .phandau {
        background: linear-gradient(135deg, #b88a44 0%, #8b6b2f 100%);
        padding: 20px 0;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 10vh;
    }

    .phandau h1 {
        text-align: center;
        font-size: 3rem;
        margin-bottom: 30px;
        letter-spacing: 2px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .phandaua {
        display: flex;
        justify-content: center;
        gap: 100px;
        margin-top: 40px;
        position: relative;
    }

    .phandaua::before {
        content: "";
        position: absolute;
        top: 17px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.3);
        z-index: 0;
    }

    .phandaua1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .phandaua1 span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #b88a44; /* Màu nền mặc định cho số 1 và 3 */
        color: white; /* Màu chữ mặc định cho số 1 và 3 */
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .phandaua1:hover span {
        transform: scale(1.1);
    }

    .phandaua1.active span {
        background-color: #b88a44;
        color: white;
    }

    /* Chỉnh màu số 2 thành #b88a44 */
    .phandaua1:nth-child(1) span {
        background-color: white;
        color: #b88a44;
    }

    .phandaua1 p {
        font-size: 1.2rem;
        font-weight: 500;
        color: white;
        text-align: center;
    }

    .phanthanmot {
        text-align: center;
        padding: 20px;
        background-color: white;
        margin: 30px auto;
        max-width: 900px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .phanthanmot p {
        text-align: center;
        font-size: 1.3rem;
        color: #e74c3c;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .complete-payment {
        text-align: center;
        margin-top: 20px;
    }

    .btn-complete {
        background-color: #4CAF50; /* Màu xanh */
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 1.2rem;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-complete:hover {
        background-color: #45a049; /* Màu xanh đậm khi hover */
    }
</style>

<div class="phandau">
    <div class="container">
        <div class="phandaua">
            <!-- Bước 1 -->
            <div class="phandaua1 {{ $step == 1 ? 'active' : '' }}">
                <span>1</span>
                <p>Thông tin khách hàng</p>
            </div>
            <!-- Bước 2 -->
            <div class="phandaua1 {{ $step == 2 ? 'active' : '' }}">
                <span>2</span>
                <p>Chi tiết thanh toán</p>
            </div>
            <!-- Bước 3 -->
            <div class="phandaua1 {{ $step == 3 ? 'active' : '' }}">
                <span>3</span>
                <p>Xác nhận đặt phòng</p>
            </div>
        </div>
    </div>
</div>
