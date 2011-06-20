#import <UIKit/UIKit.h>
#import "CommonViewControllerBase.h"

@interface SetReminderViewController : CommonViewControllerBase <UITextViewDelegate> {

	IBOutlet UITextView* textView;
	NSString* submitLabelText;
	IBOutlet UIDatePicker* datePicker;
	IBOutlet UILabel* submitLabel;
	UILocalNotification* locNot;
	NSString* actionStr;
}

@property (nonatomic, retain) UITextView* textView;
@property (nonatomic, retain) NSString* submitLabelText;
@property (nonatomic, retain) UIDatePicker* datePicker;
@property (nonatomic, retain) UILabel* submitLabel;
@property (nonatomic, retain) UILocalNotification* locNot;
@property (nonatomic, retain) NSString* actionStr;

- (IBAction) setReminder;
- (void) animateTextField: (UITextView*) textField up: (BOOL) up;

@end
