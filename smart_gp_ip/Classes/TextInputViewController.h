#import <UIKit/UIKit.h>

@interface TextInputViewController : UIViewController {
	
	IBOutlet UITextView* textView;
	IBOutlet UITextField* targetTextView;

}

@property (nonatomic, retain) UITextView* textView;
@property (nonatomic, retain) UITextField* targetTextView;

- (IBAction) okAction: (id) sender;

@end
