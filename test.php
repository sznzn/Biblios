<?php
// 关于wordpress添加 部分代码的方法
//添加 shortcode 函数

function display_symfony_formations_shortcode() {
    //获取数据
    $symfony_db = new wpdb(
        SYMFONY_DB_USER,
        SYMFONY_DB_PASSWORD,
        SYMFONY_DB_NAME,
        SYMFONY_DB_HOST
    );
    $results = $symfony_db->get_results("
    SELECT * FROM symfony_formations
    WHERE status = 'active'
    ORDER BY created_at DESC
    LIMIT 3
    ");
    //开始输出缓冲
    ob_start();
    ?>
    <div class="formations-grid">
        <?php foreach ($results as $result): ?>
            <div class="formation-card">
                <h3><?php echo $result->title; ?></h3>
                <p><?php echo $result->description; ?></p>
                    <a href="https://你的symfony网站.com/register/<?php echo $result->id; ?>">
                    S'inscrire
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    //获取缓冲区的内容
    $output = ob_get_clean();
    //返回数据
    return $output;
}

//添加 shortcode
add_shortcode('symfony_formations', 'display_symfony_formations_shortcode');


// 想放哪里就添加 [symfony_formations]

// functions.php
// 添加 Elementor 小部件（可选）
function register_symfony_formations_widget( $widgets_manager ) {
    class Symfony_Formations_Widget extends \Elementor\Widget_Base {
        public function get_name() {
            return 'symfony_formations';
        }

        public function get_title() {
            return 'Formations List';
        }

        public function get_icon() {
            return 'eicon-posts-grid';
        }

        public function get_categories() {
            return [ 'basic' ];
        }

        protected function render() {
            echo do_shortcode('[symfony_formations]');
        }
    }

    $widgets_manager->register( new Symfony_Formations_Widget() );
}
add_action( 'elementor/widgets/register', 'register_symfony_formations_widget' );