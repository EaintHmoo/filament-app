<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
                ->schema([
                    Section::make('Create Post')
                    ->description('This is description')
                    ->collapsible()
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('slug'),
                        ColorPicker::make('color'),
                        MarkdownEditor::make('content'),
                        Select::make('category_id')
                                ->label('category')
                                ->options(Category::pluck('name','id')->toArray())
                    ])->columnSpan(2),
                Group::make()
                ->schema([
                    Section::make('Meta')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails')
                    ]),
                    Section::make('Others')
                    ->schema([
                        TagsInput::make('tags'),
                        Checkbox::make('published'),
                    ])
                ])   
                ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('id')
                ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('title')
                ->toggleable()
                ->searchable()
                ->sortable(),
                TextColumn::make('slug')
                ->toggleable()
                ->searchable()
                ->sortable(),
                ColorColumn::make('color')
                ->toggleable(),
                TextColumn::make('content')
                ->toggleable(),
                TextColumn::make('category.name')
                ->toggleable()
                ->searchable()
                ->sortable(),
                TextColumn::make('tags')
                ->toggleable()
                ->searchable()
                ->sortable(),
                CheckboxColumn::make('published')
                ->toggleable(),
                ImageColumn::make('thumbnail')
                ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
