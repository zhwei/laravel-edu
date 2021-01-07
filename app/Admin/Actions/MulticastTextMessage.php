<?php

namespace App\Admin\Actions;

use App\Notifications\StudentNotify;
use App\Services\LineService;
use App\Student;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MulticastTextMessage extends BatchAction
{
    public $name = '推送消息';

    public function form()
    {
        $options = ['yes' => '是', 'no' => '否'];
        $selected = 'yes';

        $this->textarea('content', '消息内容')->rules('required')->required();
        $this->radio('site_notify', '站内信')
            ->options($options)->default($selected)
            ->rules('required')->required();
        $this->radio('line_notify', 'Line')
            ->options($options)->default($selected)
            ->rules('required')->required();
    }

    /**
     * @param Collection|Student[] $collection
     * @param Request $request
     * @return \Encore\Admin\Actions\Response
     */
    public function handle(Collection $collection, Request $request)
    {
        $content = $request->get('content');
        $siteNotify = $request->get('site_notify') === 'yes';
        $lineNotify = $request->get('line_notify') === 'yes';
        if (!$siteNotify && !$lineNotify) {
            return $this->response()->error('未选中推送方式');
        }

        if ($lineNotify) {
            if (!$lineIds = $collection->pluck('line_id')->unique()->all()) {
                return $this->response()->error('发送失败，所有学生均未绑定 line');
            }
            $line = new LineService();
            $line->multicastTextMessage($lineIds, $content);
        }
        if ($siteNotify) {
            $msg = new StudentNotify($content);
            foreach ($collection as $student) {
                $student->notify($msg);
            }
        }

        return $this->response()->success("推送成功")->refresh();
    }
}
