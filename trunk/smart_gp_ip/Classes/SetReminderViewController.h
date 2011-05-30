#import <UIKit/UIKit.h>


@interface SetReminderViewController : UIViewController <UITextViewDelegate> {

	IBOutlet UITextView* textView;
	NSString* submitLabelText;
	IBOutlet UIDatePicker* datePicker;
	IBOutlet UILabel* submitLabel;
}

@property (nonatomic, retain) UITextView* textView;
@property (nonatomic, retain) NSString* submitLabelText;
@property (nonatomic, retain) UIDatePicker* datePicker;
@property (nonatomic, retain) UILabel* submitLabel;

- (IBAction) setReminder;
- (void) animateTextField: (UITextView*) textField up: (BOOL) up;

@end
