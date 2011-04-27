//
//  editNameViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editNameViewController : UIViewController {

	IBOutlet UITextField * nameField ;
	BOOL avanzar ;
}


-(IBAction)saveAction:(id)sender ;


-(NSString *) dataFilePath ;

@end
