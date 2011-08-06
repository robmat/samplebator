#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface TextInputViewController : VCBase <UITextViewDelegate> {

@public	
	IBOutlet UITextView* textView;
	IBOutlet UITextField* targetTextView;
	IBOutlet UILabel* countLbl;
	int editCount;
	BOOL delTextAtFirstEdit;
}

@property (nonatomic, retain) IBOutlet UITextView* textView;
@property (nonatomic, retain) IBOutlet UITextField* targetTextView;
@property (nonatomic, retain) IBOutlet UILabel* countLbl;

- (IBAction) okAction: (id) sender;

@end
