<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use App\Models\Area;
use App\Models\Scholar;
use App\Models\Query;
use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Filament\Forms\Components\Select;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Forms\Components\Textarea;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use DB;
use Illuminate\Support\Str; // Add this use statement
use Filament\Forms\Get;


class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    // protected static ?string $navigationGroup = 'Lead Management';

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TextInput::make('university'),
                Forms\Components\Select::make('source')
                ->options([
                    'Facebook' => 'Facebook',
                    'Website' => 'Website',
                    'Other' => 'Other',
                ])->default('Facebook'),
                Forms\Components\TextInput::make('notes'),
                Forms\Components\Select::make('status')
                ->options([
                    'Open' => 'Open',
                    'Closed' => 'Closed',
                ])->default('Open'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
                ->forceSearchCaseInsensitive(),
                Tables\Columns\TextColumn::make('phone')->copyable()
                ->copyMessage('Phone Number copied')
                ->copyMessageDuration(1500)   ->searchable()
                ->forceSearchCaseInsensitive(),
                Tables\Columns\TextColumn::make('email') ->copyable()
                ->copyMessage('Email address copied')
                ->copyMessageDuration(1500)->searchable()->toggleable(isToggledHiddenByDefault: true)
                ->forceSearchCaseInsensitive(),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('university'),
                
                Tables\Columns\TextColumn::make('source')  ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('support.name'),

                Tables\Columns\TextColumn::make('status')->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Open' => 'gray',
                    'Processing' => 'warning',
                    'Converted' => 'success',
                    'Closed' => 'danger',
                    default => 'primary', // You can provide a default color if needed
                })
                
            ])
            ->filters([
                //

            ])
            ->actions([
                Action::make('Follow Up')->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->fillForm(fn (Lead $record): array => [
                'notes' => $record->notes,
                // 'status'=>$record->status,
                ])
                ->form([

                    Forms\Components\Select::make('status')
                    ->options([
                        'Processing'=>'Processing',
                        'Closed'=>'Closed',
                    ])->default('Processing'),

                    TextArea::make('notes')
                ->label('Remark')->autoSize()                
                ->required(),
                ])
                ->action(function (array $data, Lead $record): void {
                $record->notes = $data['notes'];
                $record->status = $data['status'];

             
                $record->update();
                Notification::make()
                ->title("Follow up updated Successfully.")
                ->success()
                ->send();
                }),
                Action::make('Convert') ->icon('heroicon-m-wrench')->fillForm(fn (Lead $record): array => [
                    'name' => $record->name,
                    'phone' => $record->phone,
                    'email' => $record->email,
                    'city' => $record->city,
                    'university' => $record->university,
                    'source' => $record->source,
                    'notes' => $record->notes,                    
                ])
                    ->steps([
                        Step::make('Lead')
                            ->description('Please fill required details.')
                            ->schema([
                                TextInput::make('name')
                                ->required(),
                                TextInput::make('phone')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                ->required(),
                                TextInput::make('email')
                                ->required(),
                                TextInput::make('university'),
                                TextInput::make('city'),
                                TextInput::make('source'),
                                TextInput::make('notes'),              
                            ])
                            ->columns(2),
                        Step::make('Scholar')
                            ->description('Please fill scholar\'s details.')
                            ->schema([
                                TextInput::make('name')
                                ->required(),
                                TextInput::make('email')
                                ->required(),
                                TextInput::make('phone')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')->required(),
                                TextInput::make('alternate_phone')->label('Alternate Phone')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                ,                                
                                Select::make('gender')
                                ->label('Gender')
                                ->options(['Male','Female']),
                                TextInput::make('university'),
                                TextInput::make('address1')->label('Address Line1'),
                                TextInput::make('address2')->label('Address Line2'),
                                TextInput::make('city'),
                        ])
                            ->columns(2),
                        Step::make('Query')
                            ->description('Please fill query details.')
                            ->schema([
                               TextInput::make('title')->required(),                            
                               TextInput::make('specification')->required()
                               ,
                                Select::make('area_id')->label('Area')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => Area::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                ->getOptionLabelUsing(fn ($value): ?string => Area::find($value)?->name)->required(),
                               
                               DatePicker::make('dod')->label('Date of Delivery'),
                               TextInput::make('remarks'), //MarkdownEditor::make('description')
                               Select::make('lang')
                               ->label('Language')
                               ->options(['English','Hindi']),
                               Toggle::make('quotation_sent')
                               ->onIcon('heroicon-o-paper-airplane')
                               ->onColor('success')
                               ->offColor('danger')->live(),
                               TagsInput::make('tags'),
                               
                               TextInput::make('total_amount')->suffixIcon('heroicon-m-calculator')->label('Total Amount')
                               ->hidden(fn (Get $get): bool => ! $get('quotation_sent'))->required(),
                               
                            ])->columns(2),
                        ])->action(function (array $data, Lead $record): void {
                            //$record->author()->associate($data['authorId']);
                            try{
                                DB::beginTransaction();

                                $scholar = Scholar::firstOrNew(['email'=>$data['email'],'mobile' => $data['phone']]);
                                $scholar->name = $data['name'];
                                $scholar->university = $data['university'];
                                $scholar->city = $data['city'];
                                $scholar->mobile = $data['phone'];
                                $scholar->mobile2 = $data['alternate_phone'];
                                $scholar->address1 = $data['address1'];
                                $scholar->address2 = $data['address2'];
                                $scholar->gender = $data['gender'];
                                $scholar->save();
                                
                                $qid = IdGenerator::generate(['table' => 'queries', 'reset_on_prefix_change' => true, 'field' => 'qid', 'length' => 15, 'prefix' => $scholar->sid.'-']);
                                
                                $query = new Query();
                                $query->qid =$qid;
                                $query->source = $data['source'];
                                $query->title = $data['title'];
                                $query->specification = $data['specification'];
                                $query->dod = $data['dod'];
                                $query->remarks = $data['remarks'];
                                $query->lang = $data['lang'];
                                $query->tags = $data['tags'];
                                $query->area_id = $data['area_id'];
                                $query->scholar_id = $scholar->id;
                                $query->quotation_sent = $data['quotation_sent'];
                                $query->save();

                                if($data['quotation_sent']){
                                
                                    $wid = IdGenerator::generate(['table' => 'works', 'reset_on_prefix_change' => true, 'field' => 'wid', 'length' => 14, 'prefix' => Str::of($query->scholar->sid)->replace('S', 'W') . '-']);

                                    $work = new Work();
                                    $work->wid = $wid;
                                    $work->note = $data['remarks'];
                                    $work->total_amount = $data['total_amount'];
                                    $work->end_date = $query->dod;
                                    $work->query_id = $query->id;
                                    $work->scholar_id = $scholar->id;
                                    $work->save();
                                
                                }

                                $record->status = "Converted";
                                $record->update();
                                
                                DB::commit(); //save changes

                                Notification::make()
                                ->title($data['quotation_sent'] ? 'Lead transffered to work for approval.' :"Lead transffered to query followups.")
                                ->success()
                                ->send();

                            }
                            catch(\Exception $e){
                                DB::rollback();

                                Notification::make()
                                ->title("Lead transffered Error: ".$e->getMessage())
                                ->danger()
                                ->send();
                            }
                        }),

                        Tables\Actions\EditAction::make(), 

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                
                FilamentExportHeaderAction::make('export')->icon('heroicon-m-arrow-down-on-square')->iconButton()->tooltip('Export'),
                
                BulkAction::make('Assign')
                ->requiresConfirmation()->icon('heroicon-m-arrow-path-rounded-square')
                ->form([
                    Select::make('support_id')
                    ->label('Support')
                    ->options(User::query()->pluck('name', 'id'))
                    ->required(),
                    
                ])->action(function($data,Collection $records){
                    
                    Lead::whereIn('id',$records->pluck('id'))->update(['support_id'=>$data['support_id']]);
                    Notification::make()
                    ->title("Bulk lead assigned successfully.")
                    ->success()
                    ->send();                

                })->iconButton()->tooltip('Assign'),
             ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }   
    public static function getWidgets(): array
{
    return [
        LeadResource\Widgets\LeadStatusOverview::class,
    ];
} 
}
