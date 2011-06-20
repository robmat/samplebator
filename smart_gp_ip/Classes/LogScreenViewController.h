#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import "CommonViewControllerBase.h"

@interface LogScreenViewController : CommonViewControllerBase <UITextFieldDelegate, UIPickerViewDelegate, UIPickerViewDataSource> {

	IBOutlet UITextField* date;
	IBOutlet UITextField* timeSpent;
	IBOutlet UITextField* activityType;
	IBOutlet UITextField* logTitle;
	IBOutlet UITextField* description;
	IBOutlet UITextField* lessonsLearnt;
	UIDatePicker* datePickerView;
	UIPickerView* timespentView;
	NSInteger timeSpentRowSelection;
	UIPickerView* activityPickerView;
	NSInteger activityRowSelection;
	NSDictionary* log;
}

@property (nonatomic, retain) UITextField* date;
@property (nonatomic, retain) UITextField* timeSpent;
@property (nonatomic, retain) UITextField* activityType;
@property (nonatomic, retain) UITextField* logTitle;
@property (nonatomic, retain) UITextField* description;
@property (nonatomic, retain) UITextField* lessonsLearnt;
@property (nonatomic, retain) NSDictionary* log;

- (void) animateTextField: (UITextField*) textField up: (BOOL) up;
- (IBAction) saveAction: (id) sender;
- (IBAction) viewLogsAction: (id) sender;
+ (NSString*) getFilePath;
- (IBAction) lessonsLearntAction: (id) sender;
- (IBAction) descrptionAction: (id) sender;

@end
